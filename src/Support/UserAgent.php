<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Support;

use FriendsOfHyperf\Jet\Client;
use GuzzleHttp\ClientInterface;

class UserAgent
{
    /**
     * @var null|string
     */
    protected static $value;

    public static function set(string $value): void
    {
        self::$value = $value;
    }

    public static function get(): string
    {
        return self::$value ?: sprintf(
            'jet/%s php/%s guzzle/%s curl/%s',
            Client::MAJOR_VERSION,
            PHP_VERSION,
            defined(ClientInterface::class . '::VERSION') ? constant(ClientInterface::class . '::VERSION') : constant(ClientInterface::class . '::MAJOR_VERSION'),
            curl_version()['version']
        );
    }
}
