<?php

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Support\Assert;

class ServiceManager
{
    /**
     * @var array
     */
    protected static $services = array();

    /**
     * @param string $service
     * @return array
     */
    public static function get($service)
    {
        return self::isRegistered($service) ? static::$services[$service] : array();
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

    /**
     * @param RegistryInterface $registry
     * @param bool $force
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function registerDefaultRegistry($registry, $force = false)
    {
        Assert::assertRegistry($registry);

        RegistryManager::register(RegistryManager::DEFAULT_REGISTRY, $registry, $force);
    }

    /**
     * @return null|RegistryInterface
     */
    public static function getDefaultRegistry()
    {
        return RegistryManager::get(RegistryManager::DEFAULT_REGISTRY);
    }
}
