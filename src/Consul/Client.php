<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Consul;

use Closure;
use FriendsOfHyperf\Jet\Exception\ClientException;
use FriendsOfHyperf\Jet\Exception\ServerException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;

class Client
{
    public const DEFAULT_URI = 'http://127.0.0.1:8500';

    /**
     * Will execute this closure everytime when the consul client send a HTTP request,
     * and the closure should return a GuzzleHttp\ClientInterface instance.
     * $clientFactory(array $options).
     *
     * @var Closure
     */
    private $clientFactory;

    public function __construct(Closure $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @throws ServerException
     * @throws ClientException
     * @throws GuzzleException
     */
    public function request(string $method = 'GET', string $url = '', array $options = []): Response
    {
        try {
            // Create a HTTP Client by $clientFactory closure.
            $clientFactory = $this->clientFactory;
            $client = $clientFactory($options);

            if (! $client instanceof ClientInterface) {
                throw new ClientException(sprintf('The client factory should create a %s instance.', ClientInterface::class));
            }

            $response = $client->request($method, $url, $options);
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling consul (%s).', $e->getMessage());

            throw new ServerException(['message' => $e->getMessage(), 'code' => $e->getCode()], $e);
        }

        if ($response->getStatusCode() >= 400) {
            $message = sprintf('Something went wrong when calling consul (%s - %s).', $response->getStatusCode(), $response->getReasonPhrase());
            $message .= PHP_EOL . (string) $response->getBody();

            if ($response->getStatusCode() >= 500) {
                throw new ServerException(['message' => $message, 'code' => $response->getStatusCode()]);
            }

            throw new ClientException($message, $response->getStatusCode());
        }

        return new Response($response);
    }

    /**
     * @return array
     */
    protected function resolveOptions(array $options, array $availableOptions)
    {
        // Add key of ACL token to $availableOptions
        $availableOptions[] = 'token';

        return array_intersect_key($options, array_flip($availableOptions));
    }
}
