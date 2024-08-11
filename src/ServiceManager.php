<?php

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Support\Assert;

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
     * @return void
     * @throws \InvalidArgumentException
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
