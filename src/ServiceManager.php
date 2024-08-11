<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

class ServiceManager
{
    /**
     * @var array<string, Metadata>
     */
    protected static $services = [];

    public static function get(string $service): ?Metadata
    {
        return self::isRegistered($service) ? static::$services[$service] : null;
    }

    public static function isRegistered(string $service): bool
    {
        return isset(static::$services[$service]);
    }

    public static function register(string $service, Metadata $metadata): void
    {
        static::$services[$service] = $metadata;
    }

    public static function deregister(string $service): void
    {
        unset(static::$services[$service]);
    }
}
