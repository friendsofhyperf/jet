<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Jet\LoadBalancer;

use FriendsOfHyperf\Jet\Exception\NoNodesAvailableException;

class Random extends AbstractLoadBalancer
{
    /**
     * Select an item via the load balancer.
     */
    public function select(): Node
    {
        if (empty($this->nodes)) {
            throw new NoNodesAvailableException('Cannot select any node from load balancer.');
        }

        $key = array_rand($this->nodes);

        return $this->nodes[$key];
    }
}
