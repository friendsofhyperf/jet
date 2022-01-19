<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */
namespace FriendsOfHyperf\Jet\Transporter;

use Closure;
use Grpc\BaseStub;

class GrpcTransporter extends AbstractTransporter
{
    /**
     * @var BaseStub
     */
    protected $client;

    /**
     * @var Closure
     */
    protected $factory;

    /**
     * @var array
     */
    protected $ret;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var null|object|string
     */
    protected $credentials;

    public function __construct(string $host = '', int $port = 9501, array $config = [])
    {
        $this->host = $host;
        $this->port = $port;

        foreach ($config as $k => $v) {
            if (in_array($k, ['path', 'metadata', 'options', 'credentials'])) {
                $this->{$k} = $v;
            }
        }

        $this->config = array_merge_recursive(
            [
                'credentials' => $this->credentials,
            ],
            $config
        );

        $this->factory = function ($dsn, $config) {
            return new class($dsn, $config) extends BaseStub {
                public function __construct($dsn, $config)
                {
                    parent::__construct($dsn, $config);
                }

                public function request($method, $argument, $deserialize, array $metadata = [], array $options = [])
                {
                    return $this->_simpleRequest(...func_get_args())->wait();
                }
            };
        };
    }

    public function send(string $data)
    {
        $data = unserialize($data);
        $method = str_start($this->path, '/') . $data['method'];
        $argument = (object) $data['params'][0];
        $deserialize = $data['params'][1];

        $this->ret = $this->getClient()->request($method, $argument, $deserialize, $this->metadata, $this->options);
    }

    public function recv()
    {
        return $this->ret;
    }

    /**
     * @return BaseStub|object
     */
    public function getClient()
    {
        if (! ($this->client instanceof BaseStub)) {
            if ($this->getLoadBalancer()) {
                $node = $this->getLoadBalancer()->select();
            } else {
                $node = $this;
            }

            $factory = $this->factory;
            $this->client = $factory(sprintf('%s:%s', $node->host, $node->port), $this->config);
        }

        return $this->client;
    }
}
