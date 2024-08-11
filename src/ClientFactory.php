<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/3.x/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use Closure;
use Exception;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

class ClientFactory
{
    /**
     * User agent.
     * @var string
     */
    protected static $userAgent;

    /**
     * Set user agent.
     */
    public static function setUserAgent(string $userAgent): void
    {
        self::$userAgent = $userAgent;
    }

    /**
     * Get user agent.
     */
    public static function getUserAgent(): string
    {
        return self::$userAgent ?: sprintf(
            'jet/%s php/%s guzzle/%s curl/%s',
            Client::MAJOR_VERSION,
            PHP_VERSION,
            defined(ClientInterface::class . '::VERSION') ? constant(ClientInterface::class . '::VERSION') : constant(ClientInterface::class . '::MAJOR_VERSION'),
            curl_version()['version']
        );
    }

    /**
     * Create a client.
     * @param Closure|Metadata|string $service
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function create($service): Client
    {
        if ($service instanceof Metadata) {
            return new Client($service);
        }

        if (is_string($service)) {
            $metadata = ServiceManager::get($service) ?? new Metadata($service);
            return new Client($metadata);
        }

        if ($service instanceof Closure) {
            $metadata = $service();
            if ($metadata instanceof Metadata) {
                return new Client($metadata);
            }
        }

        throw new InvalidArgumentException('$service must been instanced of string/Closure/Metadata');
    }
}
