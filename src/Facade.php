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

use Exception;
use InvalidArgumentException;
use RuntimeException;

abstract class Facade
{
    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        // return call_user_func_array([static::getFacadeRoot(), $name], $arguments);
        return static::getFacadeRoot()->{$name}(...$arguments);
    }

    /**
     * @throws RuntimeException
     * @return Client
     */
    protected static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * @param mixed $name
     * @throws InvalidArgumentException
     * @throws Exception
     * @return mixed
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
     * @throws RuntimeException
     * @return Client|string
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
}
