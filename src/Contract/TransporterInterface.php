<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

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