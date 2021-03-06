<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */
namespace FriendsOfHyperf\Jet\LoadBalancer;

use RuntimeException;

class RoundRobin extends AbstractLoadBalancer
{
    /**
     * @var int
     */
    private static $current = 0;

    /**
     * Select an item via the load balancer.
     */
    public function select(): Node
    {
        $count = count($this->nodes);

        if ($count <= 0) {
            throw new RuntimeException('Nodes missing.');
        }

        $item = $this->nodes[self::$current % $count];
        ++self::$current;

        return $item;
    }
}
