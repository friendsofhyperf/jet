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
use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\Exception\ClientException;
use InvalidArgumentException;

class ClientFactory
{
    /**
     * timeout.
     * @var int
     */
    protected static $timeout = 5;

    /**
     * Set timeout.
     */
    public static function setTimeout(int $timeout)
    {
        if ($timeout < 0) {
            return;
        }

        self::$timeout = $timeout;
    }

    /**
     * Create a client.
     *
     * @param null|int|string|TransporterInterface $transporter transporter, protocol, timeout or null
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function create(string $service, $transporter = null, ?PackerInterface $packer = null, ?DataFormatterInterface $dataFormatter = null, ?PathGeneratorInterface $pathGenerator = null, ?int $tries = null): Client
    {
        $protocol = null;
        $timeout = self::$timeout;

        // create by transporter
        if ($transporter instanceof TransporterInterface) {
            return static::createWithTransporter(...func_get_args());
        }

        // when $transporter is string or number
        if (is_numeric($transporter)) {
            [$timeout, $transporter] = [$transporter, null];
        } elseif (is_string($transporter)) {
            [$protocol, $transporter] = [$transporter, null];
        }

        $metadatas = ServiceManager::get($service);

        if (! $metadatas) {
            if ($registry = RegistryManager::get(RegistryManager::DEFAULT)) {
                return self::createWithRegistry($service, $registry, $protocol, $packer, $dataFormatter, $pathGenerator, $tries);
            }

            throw new ClientException(sprintf('Service %s does not register yet.', $service));
        }

        if (isset($metadatas[ServiceManager::TRANSPORTER])) { // Preference to using transporter
            /** @var TransporterInterface $transporter */
            $transporter = $metadatas[ServiceManager::TRANSPORTER];
        } elseif (isset($metadatas[ServiceManager::REGISTRY])) { // Using registry when registered
            /** @var RegistryInterface|string */
            $registry = $metadatas[ServiceManager::REGISTRY];

            // Get registry from manager when it is string
            if (is_string($registry)) {
                if (! RegistryManager::isRegistered($registry)) {
                    throw new InvalidArgumentException(sprintf('Registry %s does not registered yet.', $registry));
                }

                $registry = RegistryManager::get($registry);
            }

            /** @var RegistryInterface $registry */
            $transporter = $registry->getTransporter($service, $protocol, $timeout);
        }

        if (! $transporter) {
            throw new ClientException(sprintf('Transporter of %s does not register yet.', $service));
        }

        $packer = $packer ?? $metadatas[ServiceManager::PACKER] ?? null;
        $dataFormatter = $dataFormatter ?? $metadatas[ServiceManager::DATA_FORMATTER] ?? null;
        $pathGenerator = $pathGenerator ?? $metadatas[ServiceManager::PATH_GENERATOR] ?? null;
        $tries = $tries ?? $metadatas[ServiceManager::TRIES] ?? 1;

        return static::createWithTransporter($service, $transporter, $packer, $dataFormatter, $pathGenerator, $tries);
    }

    /**
     * Create a client with transporter.
     */
    public static function createWithTransporter(string $service, TransporterInterface $transporter, ?PackerInterface $packer = null, ?DataFormatterInterface $dataFormatter = null, ?PathGeneratorInterface $pathGenerator = null, ?int $tries = null): Client
    {
        return new Client($service, $transporter, $packer, $dataFormatter, $pathGenerator, $tries);
    }

    public static function createWithRegistry(string $service, RegistryInterface $registry, ?string $protocol = null, ?PackerInterface $packer = null, ?DataFormatterInterface $dataFormatter = null, ?PathGeneratorInterface $pathGenerator = null, ?int $tries = null): Client
    {
        $transporter = $registry->getTransporter($service, $protocol, self::$timeout);

        return new Client($service, $transporter, $packer, $dataFormatter, $pathGenerator, $tries);
    }
}
