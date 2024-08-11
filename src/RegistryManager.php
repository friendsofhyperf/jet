<?php

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\RegistryInterface;

class RegistryManager
{
    const DEFAULT_REGISTRY = 'default';

    /**
     * @var array<string, RegistryInterface>
     */
    protected static $registries = array();

    /**
     * @param string $name 
     * @return RegistryInterface|null 
     */
    public static function get($name)
    {
        return isset(self::$registries[$name]) ? self::$registries[$name] : null;
    }

    /**
     * @param string $name 
     * @param RegistryInterface $registry 
     * @param bool $force 
     * @return void 
     * @throws \InvalidArgumentException 
     * @throws \RuntimeException 
     */
    public static function register($name, $registry, $force = false)
    {
        if (!($registry instanceof RegistryInterface)) {
            throw new \InvalidArgumentException('$registry must be instanceof JetRegistryInterface');
        }

        if (!$force && self::isRegistered($name)) {
            throw new \RuntimeException($name . ' has registered');
        }

        self::$registries[$name] = $registry;
    }

    /**
     * @param string $name 
     * @return bool 
     */
    public static function isRegistered($name)
    {
        return isset(self::$registries[$name]);
    }

    /**
     * @param string $name 
     * @return void 
     */
    public static function deregister($name)
    {
        unset(static::$registries[$name]);
    }
}
