<?php

namespace FriendsOfHyperf\Jet\Contract;

use FriendsOfHyperf\Jet\LoadBalancer\LoadBalancerNode;

interface LoadBalancerInterface
{
    /**
     * Select an item via the load balancer.
     * @return LoadBalancerNode
     */
    public function select();

    /**
     * @param LoadBalancerNode $nodes
     * @return $this
     */
    public function setNodes($nodes);

    /**
     * @return LoadBalancerNode[] $nodes
     */
    public function getNodes();
}