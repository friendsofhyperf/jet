<?php

/**
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

interface RegistryInterface
{
    /**
     * @param LoadBalancerInterface|null $loadBalancer
     * @return void
     */
    public function setLoadBalancer($loadBalancer);

    /**
     * @return LoadBalancerInterface|null
     */
    public function getLoadBalancer();

    /**
     * @return array
     */
    public function getServices();

    /**
     * @param string $service
     * @param string|null $protocol
     * @return array|JetLoadBalancerNode[]
     */
    public function getServiceNodes($service, $protocol = null);

    /**
     * @param string $service
     * @param string|null $protocol
     * @param int $timeout
     * @return TransporterInterface
     */
    public function getTransporter($service, $protocol = null, $timeout = 1);
}
