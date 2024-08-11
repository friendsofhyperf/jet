<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

use FriendsOfHyperf\Jet\LoadBalancer\Node;

interface RegistryInterface
{
    public function setLoadBalancer(?LoadBalancerInterface $loadBalancer);

    /**
     * @return LoadBalancerInterface|null
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
    public function getTransporter(string $service, ?string $protocol = null, array $config = []);
}
