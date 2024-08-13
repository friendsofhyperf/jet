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
     * @return Client
     * @throws ClientException
     */
    public static function create(
        $service,
        $transporter = null,
        $packer = null,
        $dataFormatter = null,
        $pathGenerator = null,
        $tries = null
    ) {
        if (!$metadata = ServiceManager::get($service)) {
            $metadata = new Metadata($service);

            if (RegistryManager::isRegistered(RegistryManager::DEFAULT_REGISTRY)) {
                $metadata->setRegistry(RegistryManager::get(RegistryManager::DEFAULT_REGISTRY));
            }

            if (is_numeric($transporter)) {
                $metadata->setTimeout($transporter);
            } elseif (is_string($transporter)) {
                $metadata->setProtocol($transporter);
            } elseif ($transporter instanceof TransporterInterface) {
                $metadata->setTransporter($transporter);
            }

            if ($packer instanceof PackerInterface) {
                $metadata->setPacker($packer);
            }

            if ($dataFormatter instanceof DataFormatterInterface) {
                $metadata->setDataFormatter($dataFormatter);
            }

            if ($pathGenerator instanceof PathGeneratorInterface) {
                $metadata->setPathGenerator($pathGenerator);
            }

            if (is_numeric($tries)) {
                $metadata->setTries($tries);
            }
        }

        return new Client($metadata);
    }
}
