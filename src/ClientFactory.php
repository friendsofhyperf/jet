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

        if (RegistryManager::isRegistered(RegistryManager::DEFAULT_REGISTRY)) {
            $metadata = $metadata->withRegistry(RegistryManager::get(RegistryManager::DEFAULT_REGISTRY));
        }

        if (is_numeric($transporter)) {
            $metadata = $metadata->withTimeout($transporter);
        } elseif (is_string($transporter)) {
            $metadata = $metadata->withProtocol($transporter);
        } elseif ($transporter instanceof TransporterInterface) {
            $metadata = $metadata->withTransporter($transporter);
        }

        if ($packer instanceof PackerInterface) {
            $metadata = $metadata->withPacker($packer);
        }

        if ($dataFormatter instanceof DataFormatterInterface) {
            $metadata = $metadata->withDataFormatter($dataFormatter);
        }

        if ($pathGenerator instanceof PathGeneratorInterface) {
            $metadata = $metadata->withPathGenerator($pathGenerator);
        }

        if (is_numeric($tries)) {
            $metadata = $metadata->withTries($tries);
        }

        if (!ServiceManager::isRegistered($service)) {
            ServiceManager::register($service, $metadata);
        }

        return new Client($metadata);
    }
}
