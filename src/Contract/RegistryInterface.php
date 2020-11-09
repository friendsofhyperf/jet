<?php

namespace Huangdijia\Jet\Contract;

use Huangdijia\Jet\Contract\LoadBalancerInterface;
use Huangdijia\Jet\LoadBalancer\Node;

interface RegistryInterface
{
    /**
     * @param LoadBalancerInterface|null $loadBalancer
     * @return void
     */
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
     * @param string $service
     * @param string|null $protocol
     * @return array|Node[]
     */
    public function getServiceNodes(string $service, ?string $protocol = null);

    /**
     * @param string $service
     * @param string|null $protocol
     * @return TransporterInterface
     */
    public function getTransporter(string $service, ?string $protocol = null);
}
