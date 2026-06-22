<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Tests;

use FriendsOfHyperf\Jet\Registry\ConsulRegistry;
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;
use FriendsOfHyperf\Jet\Transporter\MultiplexRpcTransporter;
use FriendsOfHyperf\Jet\Transporter\StreamSocketTransporter;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    private $consulUri;

    private $consulTimeout;

    private $jsonrpcHost;

    private $jsonrpcPort;

    private $jsonrpcTimeout;

    private $jsonrpcHttpHost;

    private $jsonrpcHttpPort;

    private $jsonrpcHttpTimeout;

    private $jsonrpcLengthCheckHost;

    private $jsonrpcLengthCheckPort;

    private $jsonrpcLengthCheckTimeout;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->consulUri = $_ENV['CONSUL_URI'] ?? 'http://127.0.0.1:8500';
        $this->consulTimeout = (int) ($_ENV['CONSUL_TIMEOUT'] ?? 2);

        $this->jsonrpcHost = $_ENV['JSONRPC_HOST'] ?? '127.0.0.1';
        $this->jsonrpcPort = (int) ($_ENV['JSONRPC_PORT'] ?? 9503);
        $this->jsonrpcTimeout = (int) ($_ENV['JSONRPC_TIMEOUT'] ?? 2);

        $this->jsonrpcHttpHost = $_ENV['JSONRPC_HTTP_HOST'] ?? '127.0.0.1';
        $this->jsonrpcHttpPort = (int) ($_ENV['JSONRPC_HTTP_PORT'] ?? 9502);
        $this->jsonrpcHttpTimeout = (int) ($_ENV['JSONRPC_HTTP_TIMEOUT'] ?? 2);

        $this->jsonrpcLengthCheckHost = $_ENV['JSONRPC_LENGTH_CHECK_HOST'] ?? '127.0.0.1';
        $this->jsonrpcLengthCheckPort = (int) ($_ENV['JSONRPC_LENGTH_CHECK_PORT'] ?? 9504);
        $this->jsonrpcLengthCheckTimeout = (int) ($_ENV['JSONRPC_LENGTH_CHECK_TIMEOUT'] ?? 2);
    }

    public function createGuzzleHttpTransporter()
    {
        return new GuzzleHttpTransporter($this->jsonrpcHttpHost, $this->jsonrpcHttpPort, ['timeout' => $this->jsonrpcHttpTimeout]);
    }

    public function createStreamSocketTransporter()
    {
        return new StreamSocketTransporter($this->jsonrpcHost, $this->jsonrpcPort, $this->jsonrpcTimeout);
    }

    public function createMultiplexRpcTransporter()
    {
        return new MultiplexRpcTransporter($this->jsonrpcLengthCheckHost, $this->jsonrpcLengthCheckPort, $this->jsonrpcLengthCheckTimeout);
    }

    protected function createRegistry()
    {
        return new ConsulRegistry(['uri' => $this->consulUri, 'timeout' => $this->consulTimeout]);
    }
}
