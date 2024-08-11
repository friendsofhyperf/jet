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

use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Exception\JetException;

class RegistryManager
{
    public const DEFAULT = 'default';

    /**
     * @var array
     */
    protected static $registries = [];

    /**
     * @param string $name
     * @return null|RegistryInterface
     */
    public static function get($name = self::DEFAULT)
    {
        return isset(self::$registries[$name]) ? self::$registries[$name] : null;
    }

    /**
     * @param string $name
     * @param RegistryInterface $registry
     * @throws \InvalidArgumentException
     * @throws JetException
     */
    public static function register($name, $registry, bool $force = false)
    {
        if (! $registry instanceof RegistryInterface) {
            throw new \InvalidArgumentException('$registry must be instanceof RegistryInterface');
        }

        if (! $force && self::isRegistered($name)) {
            throw new JetException(sprintf('Registry %s has registered', $name));
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
     */
    public static function deregister($name)
    {
        unset(static::$registries[$name]);
    }
}
