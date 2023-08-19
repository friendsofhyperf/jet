<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\LoadBalancer;

class Node
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

    public function __construct(string $host = '127.0.0.1', int $port = 9501, int $weight = 1, array $options = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->weight = $weight;
        $this->options = $options;
    }
}
