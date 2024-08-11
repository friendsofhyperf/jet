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

use FriendsOfHyperf\Jet\Support\UserAgent;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;

class GuzzleHttpTransporter extends AbstractTransporter
{
    /**
     * @var null|Client
     */
    protected $client;

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
        parent::__construct($host, $port);

        $this->config = array_replace([
            'http_errors' => false,
            'timeout' => $this->timeout,
        ], $config);
        $this->config['headers'] = array_replace([
            'Content-Type' => 'application/json',
            'X-Real-Ip' => $_SERVER['SERVER_ADDR'] ?? '',
            'X-Forwarded-For' => $_SERVER['REMOTE_ADDR'] ?? '',
            'User-Agent' => UserAgent::get(),
        ], $config['headers'] ?? []);
    }

    public function send(string $data)
    {
        $response = $this->client()->post('/', [
            RequestOptions::BODY => $data,
        ]);

        $this->result = $response->getBody()->getContents();
    }

    public function recv()
    {
        return $this->result;
    }

    protected function client()
    {
        if (! $this->client instanceof Client) {
            if (! isset($this->config['handler'])) {
                $this->config['handler'] = HandlerStack::create();
            }

            if (! isset($this->config['base_uri'])) {
                if ($this->getLoadBalancer()) {
                    $node = $this->getLoadBalancer()->select();
                } else {
                    $node = $this;
                }
                $this->config['base_uri'] = sprintf('http://%s:%d', $node->host, $node->port);
            }

            $this->client = new Client($this->config);
        }

        return $this->client;
    }
}
