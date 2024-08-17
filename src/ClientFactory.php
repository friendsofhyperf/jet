<?php

/*
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
use FriendsOfHyperf\Jet\Exception\ClientException;

class ClientFactory
{
    /**
     * Create a client
     * @param string $service
     * @param TransporterInterface|string|int|null $transporter
     * @param PackerInterface|null $packer
     * @param DataFormatterInterface|null $dataFormatter
     * @param PathGeneratorInterface|null $pathGenerator
     * @param int|null $tries
     * @throws ClientException
     * @return Client
     */
    public static function create(
        $service,
        $transporter = null,
        $packer = null,
        $dataFormatter = null,
        $pathGenerator = null,
        $tries = null
    ) {
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

        $metadata = new Metadata($service);

        $metadata = $metadata->when(
            RegistryManager::isRegistered(RegistryManager::DEFAULT_REGISTRY),
            function ($metadata) {
                return $metadata->withRegistry(RegistryManager::get(RegistryManager::DEFAULT_REGISTRY));
            }
        )->when(
            is_numeric($transporter),
            function ($metadata) use ($transporter) {
                return $metadata->withTimeout($transporter);
            }
        )->when(
            is_string($transporter),
            function ($metadata) use ($transporter) {
                return $metadata->withProtocol($transporter);
            }
        )->when(
            $transporter instanceof TransporterInterface,
            function ($metadata) use ($transporter) {
                return $metadata->withTransporter($transporter);
            }
        )->when(
            $packer instanceof PackerInterface,
            function ($metadata) use ($packer) {
                return $metadata->withPacker($packer);
            }
        )->when(
            $dataFormatter instanceof DataFormatterInterface,
            function ($metadata) use ($dataFormatter) {
                return $metadata->withDataFormatter($dataFormatter);
            }
        )->when(
            $pathGenerator instanceof PathGeneratorInterface,
            function ($metadata) use ($pathGenerator) {
                return $metadata->withPathGenerator($pathGenerator);
            }
        )->when(
            is_numeric($tries),
            function ($metadata) use ($tries) {
                return $metadata->withTries($tries);
            }
        )->unless(
            ServiceManager::isRegistered($service),
            function ($metadata) use ($service) {
                ServiceManager::register($service, $metadata);
            }
        );

        return new Client($metadata);
    }
}
