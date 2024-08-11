<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use Exception;
use InvalidArgumentException;
use RuntimeException;

abstract class Facade
{
    /**
     * @var array<string, Client>
     */
    protected static $instances = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getFacadeRoot()->{$name}(...$arguments);
    }

    /**
     * @return Client
     * @throws RuntimeException
     */
    protected static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * @param mixed $name
     * @return mixed
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$instances[$name])) {
            return static::$instances[$name];
        }

        return static::$instances[$name] = ClientFactory::create($name);
    }

    /**
     * @return Client|string
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
}
