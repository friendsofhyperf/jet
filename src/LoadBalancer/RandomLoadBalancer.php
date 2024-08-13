<?php

/*
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\LoadBalancer;

class RandomLoadBalancer extends AbstractLoadBalancer
{
    /**
     * Select an item via the load balancer.
     * @return LoadBalancerNode
     */
    public function select()
    {
        if (empty($this->nodes)) {
            throw new \RuntimeException('Cannot select any node from load balancer.');
        }

        $key = array_rand($this->nodes);

        return $this->nodes[$key];
    }
}
