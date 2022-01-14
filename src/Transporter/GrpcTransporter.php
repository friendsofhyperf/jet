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

class GrpcTransporter extends AbstractTransporter
{
    protected $client;

    protected $make;

    protected $ret;

    protected $path;

    protected $config = [];

    protected $metadata = [];

    protected $options = [];

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

        $this->make = function ($dsn, $config) {
            return new class($dsn, $config) extends \Grpc\BaseStub {
                public function __construct($dsn, $config)
                {
                    parent::__construct($dsn, $config);
                }

                public function SimpleRequest($params)
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
            $path = '/' . $this->path;
        }

        [$response, $status] = $this->getClient()->SimpleRequest(
            [
                $path . $data['method'],
                app()->get($data['params']),
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

    public function getClient(): \Grpc\BaseStub
    {
        if (! $this->client instanceof \Grpc\BaseStub) {
            if ($this->getLoadBalancer()) {
                $node = $this->getLoadBalancer()->select();
            } else {
                $node = $this;
            }

            $this->client = call_user_func($this->make, sprintf('%s:%s', $this->host, $this->port), $this->config);
        }

        return $this->client;
    }
}
