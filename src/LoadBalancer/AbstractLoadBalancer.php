<?php

namespace Jet\LoadBalancer;

use Jet\Contract\LoadBalancerInterface;

abstract class AbstractLoadBalancer implements LoadBalancerInterface
{
    /**
     * @var LoadBalancerNode[]
     */
    protected $nodes;

    /**
     * @param LoadBalancerNode[] $nodes
     */
    public function __construct($nodes = array())
    {
        $this->nodes = $nodes;
    }

    /**
     * @param LoadBalancerNode[] $nodes
     * @return $this
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
        return $this;
    }

    /**
     * @return LoadBalancerNode[] 
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}