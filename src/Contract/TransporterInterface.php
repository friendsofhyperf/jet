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

interface TransporterInterface
{
    public function send(string $data);

    /**
     * @return array|string
     */
    public function recv();

    public function getLoadBalancer(): ?LoadBalancerInterface;

    public function setLoadBalancer(?LoadBalancerInterface $loadBalancer): TransporterInterface;
}
