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

        $metadata = new Metadata($service);

        if (RegistryManager::isRegistered(RegistryManager::DEFAULT)) {
            $metadata = $metadata->withRegistry(RegistryManager::get(RegistryManager::DEFAULT));
        }

        if ($transporter instanceof TransporterInterface) {
            $metadata = $metadata->withTransporter($transporter);
        } elseif (is_numeric($transporter)) {
            $metadata = $metadata->withTimeout($transporter);
        } elseif (is_string($transporter)) {
            $metadata = $metadata->withProtocol($transporter);
        }

        if ($packer) {
            $metadata = $metadata->withPacker($packer);
        }

        if ($dataFormatter) {
            $metadata = $metadata->withDataFormatter($dataFormatter);
        }

        if ($pathGenerator) {
            $metadata = $metadata->withPathGenerator($pathGenerator);
        }

        if ($tries) {
            $metadata = $metadata->withTries($tries);
        }

        return new Client($metadata);
    }
}
