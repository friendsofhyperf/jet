<?php

/*
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\LoadBalancer;

class RoundRobinLoadBalancer extends AbstractLoadBalancer
{
    /**
     * @var int
     */
    private static $current = 0;

    /**
     * Select an item via the load balancer.
     * @return LoadBalancerNode
     */
    public function select()
    {
        $count = count($this->nodes);

        if ($count <= 0) {
            throw new \RuntimeException('Nodes missing.');
        }

        $item = $this->nodes[self::$current % $count];
        ++self::$current;

        return $item;
    }
}
