<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Jet\Contract;

use FriendsOfHyperf\Jet\Exception\NoNodesAvailableException;
use FriendsOfHyperf\Jet\LoadBalancer\Node;

interface LoadBalancerInterface
{
    /**
     * Select an item via the load balancer.
     * @throws NoNodesAvailableException
     */
    public function select(): Node;

    /**
     * @param Node[] $nodes
     * @return $this
     */
    public function setNodes(array $nodes);

    /**
     * @return Node[] $nodes
     */
    public function getNodes();
}
