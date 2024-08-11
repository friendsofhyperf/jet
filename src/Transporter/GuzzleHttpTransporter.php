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

use FriendsOfHyperf\Jet\ClientFactory;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use RuntimeException;

class GuzzleHttpTransporter extends AbstractTransporter
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $result;

    public function __construct(string $host = '', int $port = 9501, array $config = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->config = array_merge_recursive($config, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Real-Ip' => $_SERVER['SERVER_ADDR'] ?? '',
                'X-Forwarded-For' => $_SERVER['REMOTE_ADDR'] ?? '',
                'User-Agent' => ClientFactory::getUserAgent(),
            ],
            'http_errors' => false,
        ]);
    }

    public function send(string $data)
    {
        $response = $this->getClient()->post('/', [
            RequestOptions::BODY => $data,
        ]);

        $this->result = $response->getBody()->getContents();
    }

    public function recv()
    {
        return $this->result;
    }

    /**
     * @return Client
     * @throws RuntimeException
     */
    protected function getClient()
    {
        $config = $this->config;

        if (! isset($config['handler'])) {
            $config['handler'] = HandlerStack::create();
        }

        if (! isset($config['base_uri'])) {
            [$host, $port] = $this->getTarget();
            $config['base_uri'] = sprintf('http://%s:%d', $host, $port);
        }

        return new Client($config);
    }
}
