<?php

namespace FriendsOfHyperf\Jet;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\Exception\ClientException;

class ClientFactory
{
    /**
     * @var string
     */
    protected static $userAgent;

    /**
     * Set user-agent
     * @param string $userAgent
     * @return void
     */
    public static function setUserAgent($userAgent)
    {
        self::$userAgent = $userAgent;
    }

    /**
     * Get user-agent
     * @return string
     */
    public static function getUserAgent()
    {
        if (!is_null(self::$userAgent)) {
            return self::$userAgent;
        }

        $version = curl_version();

        return self::$userAgent = sprintf(
            'jet/%s php/%s curl/%s',
            Client::MAJOR_VERSION,
            PHP_VERSION,
            $version['version']
        );
    }

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
