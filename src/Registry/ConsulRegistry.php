<?php

namespace FriendsOfHyperf\Jet\Registry;

use FriendsOfHyperf\Jet\Consul\Catalog;
use FriendsOfHyperf\Jet\Consul\Health;
use FriendsOfHyperf\Jet\Contract\LoadBalancerInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\LoadBalancer\LoadBalancerNode;
use FriendsOfHyperf\Jet\LoadBalancer\RandomLoadBalancer;
use FriendsOfHyperf\Jet\LoadBalancer\RoundRobinLoadBalancer;
use FriendsOfHyperf\Jet\Transporter\CurlHttpTransporter;
use FriendsOfHyperf\Jet\Transporter\StreamSocketTransporter;
use FriendsOfHyperf\Jet\Util;

class ConsulRegistry implements RegistryInterface
{
    /**
     * @var array
     */
    protected $options;
    /**
     * @var LoadBalancerInterface
     */
    protected $loadBalancer;

    public function __construct($options = array())
    {
        $this->options = array_merge(array(
            'uri' => 'http://127.0.0.1:8500',
            'timeout' => 1,
            'token' => '',
        ), $options);
    }

    public function setLoadBalancer($loadBalancer)
    {
        $this->loadBalancer = $loadBalancer;
    }

    public function getLoadBalancer()
    {
        if (!$this->loadBalancer) {
            $this->loadBalancer = new RoundRobinLoadBalancer();
            $this->loadBalancer->setNodes(array(
                new LoadBalancerNode('', 0, 1, $this->options),
            ));
        }

        return $this->loadBalancer;
    }

    public function getServices()
    {
        $loadBalancer = $this->getLoadBalancer();

        return Util::retry(count($loadBalancer->getNodes()), function () use ($loadBalancer) {
            $node = $loadBalancer->select();
            $options = array();

            $options['uri'] = isset($node->options['uri']) ? $node->options['uri'] : 'http://127.0.0.1:8500';
            $options['timeout'] = isset($node->options['timeout']) ? $node->options['timeout'] : 1;

            if (!empty($node->options['token'])) {
                $options['headers'] = array(
                    'X-Consul-Token' => $node->options['token'],
                );
            }

            $consulCatalog = new Catalog($options);

            return Util::with($consulCatalog->services()->throwIf()->json(), function ($services) {
                return array_keys($services);
            });
        });
    }

    public function getServiceNodes($service, $protocol = null)
    {
        $loadBalancer = $this->getLoadBalancer();

        return Util::retry(count($loadBalancer->getNodes()), function () use ($loadBalancer, $service, $protocol) {
            $node = $loadBalancer->select();
            $options = array();

            $options['uri'] = isset($node->options['uri']) ? $node->options['uri'] : 'http://127.0.0.1:8500';
            $options['timeout'] = isset($node->options['timeout']) ? $node->options['timeout'] : 1;

            if (!empty($node->options['token'])) {
                $options['headers'] = array(
                    'X-Consul-Token' => $node->options['token'],
                );
            }

            $consulHealth = new Health($options);

            return Util::with($consulHealth->service($service)->throwIf()->json(), function ($serviceNodes) use ($protocol) {
                /** @var array $serviceNodes */
                $nodes = array();

                foreach ($serviceNodes as $node) {
                    if (Util::arrayGet($node, 'Checks.1.Status') != 'passing') {
                        continue;
                    }

                    if (!is_null($protocol) && $protocol != Util::arrayGet($node, 'Service.Meta.Protocol')) {
                        continue;
                    }

                    $nodes[] = new LoadBalancerNode(
                        Util::arrayGet($node, 'Service.Address'),
                        Util::arrayGet($node, 'Service.Port'),
                        1,
                        array(
                            'type' => Util::arrayGet($node, 'Checks.1.Type'),
                            'protocol' => Util::arrayGet($node, 'Service.Meta.Protocol'),
                        )
                    );
                }

                return $nodes;
            });
        });
    }

    public function getTransporter($service, $protocol = null, $timeout = 1)
    {
        $nodes = $this->getServiceNodes($service, $protocol);

        Util::throwIf(count($nodes) <= 0, new \RuntimeException('Service nodes not found!'));

        $serviceBalancer = new RandomLoadBalancer($nodes);
        $node = $serviceBalancer->select();

        if ($node->options['type'] == 'tcp') {
            $transporter = new StreamSocketTransporter($node->host, $node->port, $timeout);
            $serviceBalancer->setNodes(array_filter($nodes, function ($node) {
                return $node->options['type'] == 'tcp';
            }));
        } else {
            $transporter = new CurlHttpTransporter($node->host, $node->port, $timeout);
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
