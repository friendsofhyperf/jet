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
namespace FriendsOfHyperf\Jet;

use InvalidArgumentException;

class ObjectManager
{
    /**
     * @var array
     */
    protected static $services = [];

    /**
     * @return null|object
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
     * @param object $obj
     * @throws InvalidArgumentException
     */
    public static function register(string $service, $obj)
    {
        static::$services[$service] = $obj;
    }

    public static function deregister(string $service)
    {
        unset(static::$services[$service]);
    }
}
