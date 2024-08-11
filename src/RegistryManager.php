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
     * @var array<string, RegistryInterface>
     */
    protected static $registries = [];

    public static function get(string $name = self::DEFAULT): ?RegistryInterface
    {
        return isset(self::$registries[$name]) ? self::$registries[$name] : null;
    }

    /**
     * @throws JetException
     */
    public static function register(string $name, RegistryInterface $registry, bool $force = false): void
    {
        if (! $force && self::isRegistered($name)) {
            throw new JetException(sprintf('Registry %s has registered', $name));
        }

        self::$registries[$name] = $registry;
    }

    public static function isRegistered(string $name): bool
    {
        return isset(self::$registries[$name]);
    }

    public static function deregister(string $name): void
    {
        unset(static::$registries[$name]);
    }
}
