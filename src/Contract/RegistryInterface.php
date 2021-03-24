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
namespace FriendsOfHyperf\Jet\Contract;

use FriendsOfHyperf\Jet\LoadBalancer\Node;

interface RegistryInterface
{
    public function setLoadBalancer(?LoadBalancerInterface $loadBalancer);

    /**
     * @return null|LoadBalancerInterface
     */
    public function getLoadBalancer();

    /**
     * @return array
     */
    public function getServices();

    /**
     * @return array|Node[]
     */
    public function getServiceNodes(string $service, ?string $protocol = null);

    /**
     * @return TransporterInterface
     */
    public function getTransporter(string $service, ?string $protocol = null, int $timeout = 1);
}
