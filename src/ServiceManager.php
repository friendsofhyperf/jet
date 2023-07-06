<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Jet;

use InvalidArgumentException;

class ServiceManager
{
    /**
     * @var array
     */
    protected static $services = [];

    /**
     * @return Metadata|null
     */
    public static function get(string $service)
    {
        return self::isRegistered($service) ? static::$services[$service] : null;
    }

    /**
     * @return bool
     */
    public static function isRegistered(string $service)
    {
        return isset(static::$services[$service]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function register(string $service, Metadata $metadata)
    {
        static::$services[$service] = $metadata;
    }

    public static function deregister(string $service)
    {
        unset(static::$services[$service]);
    }
}
