<?php

/*
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
    protected static $services = array();

    /**
     * @param string $service
     * @return Metadata|null
     */
    public static function get($service)
    {
        return self::isRegistered($service) ? static::$services[$service] : null;
    }

    /**
     * @param string $service
     * @return bool
     */
    public static function isRegistered($service)
    {
        return isset(static::$services[$service]);
    }

    /**
     * @param string $service
     * @param Metadata $metadata
     * @throws \InvalidArgumentException
     * @return void
     */
    public static function register($service, $metadata)
    {
        static::$services[$service] = $metadata;
    }

    /**
     * @param string $service
     * @param string $protocol
     * @return void
     */
    public static function deregister($service)
    {
        unset(static::$services[$service]);
    }
}
