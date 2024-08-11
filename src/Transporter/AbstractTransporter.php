<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Transporter;

use FriendsOfHyperf\Jet\Contract\LoadBalancerInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use InvalidArgumentException;

abstract class AbstractTransporter implements TransporterInterface
{
    /**
     * @var LoadBalancerInterface|null
     */
    protected $loadBalancer;

    public function __construct(
        protected string $host = '127.0.0.1',
        protected int $port = 9502,
        protected int $timeout = 1
    ) {
    }

    public function getLoadBalancer(): ?LoadBalancerInterface
    {
        return $this->loadBalancer;
    }

    public function setLoadBalancer(?LoadBalancerInterface $loadBalancer): TransporterInterface
    {
        $this->loadBalancer = $loadBalancer;

        return $this;
    }

    /**
     * @return (int|string)[]
     * @throws InvalidArgumentException
     */
    protected function getTarget()
    {
        if ($this->getLoadBalancer()) {
            $node = $this->getLoadBalancer()->select();
        } else {
            $node = $this;
        }

        if (! $node->host || ! $node->port) {
            throw new InvalidArgumentException(sprintf('Invalid host %s or port %s.', $node->host, $node->port));
        }

        return [$node->host, $node->port];
    }
}
