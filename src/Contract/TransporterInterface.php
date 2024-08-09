<?php

namespace Jet\Contract;

interface TransporterInterface
{
    /**
     * @param string $data 
     * @return void 
     */
    public function send($data);

    /**
     * @return string 
     */
    public function recv();

    /**
     * @return LoadBalancerInterface 
     */
    public function getLoadBalancer();

    /**
     * @param LoadBalancerInterface $loadBalancer 
     * @return TransporterInterface 
     */
    public function setLoadBalancer($loadBalancer);

}