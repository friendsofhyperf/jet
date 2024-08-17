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

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;

class ClientFactory
{
    /**
     * Create a client.
     * @param null|int|string|TransporterInterface $transporter transporter, protocol, timeout or null
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function create(
        string $service,
        $transporter = null,
        ?PackerInterface $packer = null,
        ?DataFormatterInterface $dataFormatter = null,
        ?PathGeneratorInterface $pathGenerator = null,
        ?int $tries = null
    ): Client {
        if ($metadata = ServiceManager::get($service)) {
            return new Client($metadata);
        }

        if (
            func_num_args() == 2
            && is_string($transporter)
            && $metadata = MetadataManager::get($transporter)
        ) {
            return new Client($metadata->withName($service));
        }

        $metadata = (new Metadata($service))
            ->when(RegistryManager::isRegistered(RegistryManager::DEFAULT), function (Metadata $metadata) {
                return $metadata->withRegistry(RegistryManager::get(RegistryManager::DEFAULT));
            })
            ->when(is_numeric($transporter), function (Metadata $metadata) use ($transporter) {
                return $metadata->withTimeout($transporter);
            })
            ->when(is_string($transporter), function (Metadata $metadata) use ($transporter) {
                return $metadata->withProtocol($transporter);
            })
            ->when($transporter instanceof TransporterInterface, function (Metadata $metadata) use ($transporter) {
                return $metadata->withTransporter($transporter);
            })
            ->when($packer instanceof PackerInterface, function (Metadata $metadata) use ($packer) {
                return $metadata->withPacker($packer);
            })
            ->when($dataFormatter instanceof DataFormatterInterface, function (Metadata $metadata) use ($dataFormatter) {
                return $metadata->withDataFormatter($dataFormatter);
            })
            ->when($pathGenerator instanceof PathGeneratorInterface, function (Metadata $metadata) use ($pathGenerator) {
                return $metadata->withPathGenerator($pathGenerator);
            })
            ->when(is_numeric($tries), function (Metadata $metadata) use ($tries) {
                return $metadata->withTries($tries);
            })
            ->unless(ServiceManager::isRegistered($service), function (Metadata $metadata) use ($service) {
                ServiceManager::register($service, $metadata);
            });

        return new Client($metadata);
    }
}
