<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Registry;

use FriendsOfHyperf\Jet\Consul\Catalog;
use FriendsOfHyperf\Jet\Consul\Health;
use FriendsOfHyperf\Jet\Contract\LoadBalancerInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\LoadBalancer\Node;
use FriendsOfHyperf\Jet\LoadBalancer\RoundRobin;
use FriendsOfHyperf\Jet\Transporter\GrpcTransporter;
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;
use FriendsOfHyperf\Jet\Transporter\StreamSocketTransporter;
use GuzzleHttp\Client;
use RuntimeException;

use function FriendsOfHyperf\Jet\array_get;
use function FriendsOfHyperf\Jet\retry;
use function FriendsOfHyperf\Jet\with;

class ConsulRegistry implements RegistryInterface
{
    protected array $options;

    protected ?LoadBalancerInterface $loadBalancer = null;

    /**
     * @param array $options ['uri' => 'http://127.0.0.1:8500', 'timeout' => 1]
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'uri' => 'http://127.0.0.1:8500',
            'timeout' => 1,
            'token' => '',
        ], $options);
    }

    public function setLoadBalancer(?LoadBalancerInterface $loadBalancer): void
    {
        $this->loadBalancer = $loadBalancer;
    }

    public function getLoadBalancer(): LoadBalancerInterface
    {
        if (! $this->loadBalancer) {
            $this->loadBalancer = new RoundRobin();
            $this->loadBalancer->setNodes([
                new Node('', 0, 1, $this->options),
            ]);
        }

        return $this->loadBalancer;
    }

    public function getServices()
    {
        $loadBalancer = $this->getLoadBalancer();

        return retry(count($loadBalancer->getNodes()), function () use ($loadBalancer) {
            $catalog = new Catalog(function () use ($loadBalancer) {
                /** @var LoadBalancerInterface $loadBalancer */
                $node = $loadBalancer->select();

                $options = [];
                $options['base_uri'] = $node->options['uri'];
                $options['timeout'] = $node->options['timeout'] ?? 1;

                if (! empty($node->options['token'])) {
                    $options['headers'] = [
                        'X-Consul-Token' => $node->options['token'],
                    ];
                }

                return new Client($options);
            });

            return with($catalog->services()->json(), function ($services) {
                return array_keys($services);
            });
        });
    }

    public function getServiceNodes(string $service, ?string $protocol = null)
    {
        $loadBalancer = $this->getLoadBalancer();

        return retry(count($loadBalancer->getNodes()), function () use ($loadBalancer, $service, $protocol) {
            $health = new Health(function () use ($loadBalancer) {
                $node = $loadBalancer->select();

                $options = [];
                $options['base_uri'] = $node->options['uri'];
                $options['timeout'] = $node->options['timeout'] ?? 1;

                if (! empty($node->options['token'])) {
                    $options['headers'] = [
                        'X-Consul-Token' => $node->options['token'],
                    ];
                }

                return new Client($options);
            });

            return with($health->service($service)->json(), function ($serviceNodes) use ($protocol) {
                /** @var array $serviceNodes */
                $nodes = [];

                foreach ($serviceNodes as $node) {
                    if (array_get($node, 'Checks.1.Status') != 'passing') {
                        continue;
                    }

                    if (! is_null($protocol) && $protocol != array_get($node, 'Service.Meta.Protocol')) {
                        continue;
                    }

                    $nodes[] = new Node(
                        array_get($node, 'Service.Address'),
                        (int) array_get($node, 'Service.Port'),
                        1,
                        [
                            'type' => array_get($node, 'Checks.1.Type'),
                            'protocol' => array_get($node, 'Service.Meta.Protocol'),
                        ]
                    );
                }

                return $nodes;
            });
        });
    }

    public function getTransporter(string $service, ?string $protocol = null, array $config = [])
    {
        $nodes = $this->getServiceNodes($service, $protocol);

        if (count($nodes) <= 0) {
            throw new RuntimeException('Service nodes not found!');
        }

        $serviceBalancer = new RoundRobin($nodes);
        $node = $serviceBalancer->select();

        if (isset($node->options['protocol']) && $node->options['protocol'] == 'grpc') {
            $transporter = new GrpcTransporter($node->host, $node->port, $config);
            $serviceBalancer->setNodes(array_filter($nodes, function ($node) {
                return $node->options['type'] == 'grpc';
            }));
        } elseif ($node->options['type'] == 'tcp') {
            $transporter = new StreamSocketTransporter($node->host, $node->port, $config['timeout'] ?? 1);
            $serviceBalancer->setNodes(array_filter($nodes, function ($node) {
                return $node->options['type'] == 'tcp';
            }));
        } else {
            $transporter = new GuzzleHttpTransporter($node->host, $node->port, $config);
            $serviceBalancer->setNodes(array_filter($nodes, function ($node) {
                return $node->options['type'] == 'http';
            }));
        }

        if (count($nodes) > 1) {
            $transporter->setLoadBalancer($serviceBalancer);
        }

        return $transporter;
    }
}
