<?php

/**
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Transporter;

use FriendsOfHyperf\Jet\Contract\LoadBalancerInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;

abstract class AbstractTransporter implements TransporterInterface
{
    /**
     * @var string
     */
    public $host = '127.0.0.1';

    /**
     * @var int
     */
    public $port = 9502;

    /**
     * The seconds of timeout
     * @var int
     */
    public $timeout = 1;

    /**
     * @var null|LoadBalancerInterface
     */
    protected $loadBalancer;

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     */
    public function __construct($host = '127.0.0.1', $port = 9502, $timeout = 1)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * @return null|LoadBalancerInterface
     */
    public function getLoadBalancer()
    {
        return $this->loadBalancer;
    }

    /**
     * @param LoadBalancerInterface $loadBalancer
     * @return $this
     */
    public function setLoadBalancer($loadBalancer)
    {
        $this->loadBalancer = $loadBalancer;

        return $this;
    }
}
