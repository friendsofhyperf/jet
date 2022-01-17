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
use FriendsOfHyperf\Jet\ObjectManager;
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
     * @var string
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

                public function simpleRequest($params)
                {
                    return $this->_simpleRequest(...$params)->wait();
                }
            };
        };
    }

    public function send(string $data)
    {
        $data = json_decode($data, true);

        if (! starts_with($this->path, '/')) {
            $this->path = '/' . $this->path;
        }

        [$response, $status] = $this->getClient()->simpleRequest(
            [
                $this->path . $data['method'],
                ObjectManager::get($data['params']),
                $data['deserialize'],
                $this->metadata,
                $this->options,
            ]
        );

        $this->ret = [$response, $status];
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
