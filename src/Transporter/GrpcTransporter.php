<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Transporter;

use Closure;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Packer\GrpcPacker;
use FriendsOfHyperf\Jet\Support\Str;
use Grpc\BaseStub;

class GrpcTransporter extends AbstractTransporter
{
    /**
     * @var Closure
     */
    protected $clientFactory;

    protected array $ret;

    protected string $path;

    protected array $config = [];

    protected array $metadata = [];

    protected array $options = [];

    /**
     * @var object|string|null
     */
    protected $credentials;

    protected PackerInterface $packer;

    public function __construct(string $host = '', int $port = 9501, array $config = [])
    {
        parent::__construct($host, $port);

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

        $this->packer = new GrpcPacker();

        $this->clientFactory = function ($dsn, $config) {
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
        $data = $this->packer->unpack($data);
        $method = Str::start($this->path, '/') . $data['method'];
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
    protected function getClient()
    {
        $factory = $this->clientFactory;
        [$host, $port] = $this->getTarget();

        return $factory(sprintf('%s:%s', $host, $port), $this->config);
    }
}
