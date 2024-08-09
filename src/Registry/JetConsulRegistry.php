<?php

namespace Jet\Registry;

use Jet\Consul\Catalog;
use Jet\Consul\Health;
use Jet\Contract\JetLoadBalancerInterface;
use Jet\Contract\JetRegistryInterface;
use Jet\LoadBalancer\JetLoadBalancerNode;
use Jet\LoadBalancer\JetRandomLoadBalancer;
use Jet\LoadBalancer\JetRoundRobinLoadBalancer;
use Jet\Transporter\JetCurlHttpTransporter;
use Jet\Transporter\JetStreamSocketTransporter;
use Jet\Util as JetUtil;

class JetConsulRegistry implements JetRegistryInterface
{
    /**
     * @var array
     */
    protected $options;
    /**
     * @var JetLoadBalancerInterface
     */
    protected $loadBalancer;

    public function __construct($options = array())
    {
        $this->options = array_merge(array(
            'uri'     => 'http://127.0.0.1:8500',
            'timeout' => 1,
            'token'   => '',
        ), $options);
    }

    public function setLoadBalancer($loadBalancer)
    {
        $this->loadBalancer = $loadBalancer;
    }

    public function getLoadBalancer()
    {
        if (!$this->loadBalancer) {
            $this->loadBalancer = new JetRoundRobinLoadBalancer();
            $this->loadBalancer->setNodes(array(
                new JetLoadBalancerNode('', 0, 1, $this->options),
            ));
        }

        return $this->loadBalancer;
    }

    public function getServices()
    {
        $loadBalancer = $this->getLoadBalancer();

        return JetUtil::retry(count($loadBalancer->getNodes()), function () use ($loadBalancer) {
            $node    = $loadBalancer->select();
            $options = array();

            $options['uri']     = isset($node->options['uri']) ? $node->options['uri'] : 'http://127.0.0.1:8500';
            $options['timeout'] = isset($node->options['timeout']) ? $node->options['timeout'] : 1;

            if (!empty($node->options['token'])) {
                $options['headers'] = array(
                    'X-Consul-Token' => $node->options['token'],
                );
            }

            $consulCatalog = new Catalog($options);

            return JetUtil::with($consulCatalog->services()->throwIf()->json(), function ($services) {
                return array_keys($services);
            });
        });
    }

    public function getServiceNodes($service, $protocol = null)
    {
        $loadBalancer = $this->getLoadBalancer();

        return JetUtil::retry(count($loadBalancer->getNodes()), function () use ($loadBalancer, $service, $protocol) {
            $node = $loadBalancer->select();
            $options = array();

            $options['uri']     = isset($node->options['uri']) ? $node->options['uri'] : 'http://127.0.0.1:8500';
            $options['timeout'] = isset($node->options['timeout']) ? $node->options['timeout'] : 1;

            if (!empty($node->options['token'])) {
                $options['headers'] = array(
                    'X-Consul-Token' => $node->options['token'],
                );
            }

            $consulHealth = new Health($options);

            return JetUtil::with($consulHealth->service($service)->throwIf()->json(), function ($serviceNodes) use ($protocol) {
                /** @var array $serviceNodes */
                $nodes = array();

                foreach ($serviceNodes as $node) {
                    if (JetUtil::arrayGet($node, 'Checks.1.Status') != 'passing') {
                        continue;
                    }

                    if (!is_null($protocol) && $protocol != JetUtil::arrayGet($node, 'Service.Meta.Protocol')) {
                        continue;
                    }

                    $nodes[] = new JetLoadBalancerNode(
                        JetUtil::arrayGet($node, 'Service.Address'),
                        JetUtil::arrayGet($node, 'Service.Port'),
                        1,
                        array(
                            'type'     => JetUtil::arrayGet($node, 'Checks.1.Type'),
                            'protocol' => JetUtil::arrayGet($node, 'Service.Meta.Protocol'),
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

        JetUtil::throwIf(count($nodes) <= 0, new \RuntimeException('Service nodes not found!'));

        $serviceBalancer = new JetRandomLoadBalancer($nodes);
        $node            = $serviceBalancer->select();

        if ($node->options['type'] == 'tcp') {
            $transporter = new JetStreamSocketTransporter($node->host, $node->port, $timeout);
            $serviceBalancer->setNodes(array_filter($nodes, function ($node) {
                return $node->options['type'] == 'tcp';
            }));
        } else {
            $transporter = new JetCurlHttpTransporter($node->host, $node->port, $timeout);
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
