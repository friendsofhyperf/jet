<?php

namespace Jet\LoadBalancer;

class LoadBalancerNode
{
    /**
     * @var string
     */
    public $host;
    /**
     * @var int
     */
    public $port;
    /**
     * @var int
     */
    public $weight;
    /**
     * @var array
     */
    public $options;

    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     * @param array $options
     */
    public function __construct($host = '127.0.0.1', $port = 9501, $weight = 1, $options = array())
    {
        $this->host    = $host;
        $this->port    = $port;
        $this->weight  = $weight;
        $this->options = $options;
    }
}
