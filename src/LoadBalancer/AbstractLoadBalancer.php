<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\LoadBalancer;

use FriendsOfHyperf\Jet\Contract\LoadBalancerInterface;

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
