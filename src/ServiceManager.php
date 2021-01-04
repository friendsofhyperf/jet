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

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\Exception\JetException;
use InvalidArgumentException;

class ServiceManager
{
    const REGISTRY = 'rg';

    const TRANSPORTER = 'tp';

    const PACKER = 'pk';

    const DATA_FORMATTER = 'df';

    const PATH_GENERATOR = 'pg';

    const TRIES = 'ts';

    /**
     * @var array
     */
    protected static $services = [];

    /**
     * @return array
     */
    public static function get(string $service)
    {
        return self::isRegistered($service) ? static::$services[$service] : [];
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
    public static function register(string $service, array $metadatas = [])
    {
        static::assertTransporter($metadatas[static::TRANSPORTER] ?? null);
        static::assertRegistry($metadatas[static::REGISTRY] ?? null);
        static::assertPacker($metadatas[static::PACKER] ?? null);
        static::assertDataFormatter($metadatas[static::DATA_FORMATTER] ?? null);
        static::assertPathGenerator($metadatas[static::PATH_GENERATOR] ?? null);
        static::assertTries($metadatas[static::TRIES] ?? null);

        static::$services[$service] = $metadatas;
    }

    public static function deregister(string $service)
    {
        unset(static::$services[$service]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws JetException
     */
    public static function registerDefaultRegistry(RegistryInterface $registry, bool $force = false)
    {
        RegistryManager::register(RegistryManager::DEFAULT, $registry, $force);
    }

    /**
     * @return null|RegistryInterface
     */
    public static function getDefaultRegistry()
    {
        return RegistryManager::get(RegistryManager::DEFAULT);
    }

    /**
     * @param mixed $transporter
     * @throws InvalidArgumentException
     */
    public static function assertTransporter($transporter)
    {
        if (! is_null($transporter) && ! ($transporter instanceof TransporterInterface)) {
            throw new InvalidArgumentException(sprintf('Transporter of service must be instanceof %s.', TransporterInterface::class));
        }
    }

    /**
     * @param mixed $registry
     * @throws InvalidArgumentException
     */
    public static function assertRegistry($registry)
    {
        if (is_null($registry)) {
            return;
        }

        if (is_string($registry)) {
            if (! RegistryManager::isRegistered($registry)) {
                throw new InvalidArgumentException(sprintf('Registry %s does not registered yet.', $registry));
            }
        } elseif (! ($registry instanceof RegistryInterface)) {
            throw new InvalidArgumentException(sprintf('Register of service must be instanceof %s.', RegistryInterface::class));
        }
    }

    /**
     * @param mixed $packer
     * @throws InvalidArgumentException
     */
    public static function assertPacker($packer)
    {
        if (! is_null($packer) && ! ($packer instanceof PackerInterface)) {
            throw new InvalidArgumentException(sprintf('Packer of service must be instanceof %s.', PackerInterface::class));
        }
    }

    /**
     * @param mixed $dataFormatter
     * @throws InvalidArgumentException
     */
    public static function assertDataFormatter($dataFormatter)
    {
        if (! is_null($dataFormatter) && ! ($dataFormatter instanceof DataFormatterInterface)) {
            throw new InvalidArgumentException(sprintf('Service\'s DATA_FORMATTER must be instanceof %s.', DataFormatterInterface::class));
        }
    }

    /**
     * @param mixed $pathGenerator
     * @throws InvalidArgumentException
     */
    public static function assertPathGenerator($pathGenerator)
    {
        if (! is_null($pathGenerator) && ! ($pathGenerator instanceof PathGeneratorInterface)) {
            throw new InvalidArgumentException(sprintf('PathGenerator of service must be instanceof %s.', PathGeneratorInterface::class));
        }
    }

    /**
     * @param mixed $tries
     * @throws InvalidArgumentException
     */
    public static function assertTries($tries)
    {
        if (! is_null($tries) && ! is_int($tries)) {
            throw new InvalidArgumentException('Tries of service must be int.');
        }
    }
}
