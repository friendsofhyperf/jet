<?php

namespace Jet\Transporter;

use Jet\Contract\LoadBalancerInterface;
use Jet\Contract\TransporterInterface;

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
     * @var float
     */
    public $timeout = 1;

    /**
     * @var null|LoadBalancerInterface
     */
    protected $loadBalancer;

    /**
     * @param string $host
     * @param int $port
     * @param float $timeout
     */
    public function __construct($host = '127.0.0.1', $port = 9502, $timeout = 1.0)
    {
        $this->host    = $host;
        $this->port    = $port;
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
