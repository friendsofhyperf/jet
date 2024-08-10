<?php

namespace FriendsOfHyperf\Jet\Support;

use FriendsOfHyperf\Jet\Contract\DataFormatterInterface;
use FriendsOfHyperf\Jet\Contract\PackerInterface;
use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Contract\TransporterInterface;
use FriendsOfHyperf\Jet\RegistryManager;

class Assert
{

    /**
     * @param mixed $transporter
     * @throws \InvalidArgumentException
     */
    public static function assertTransporter($transporter)
    {
        if (
            !is_null($transporter)
            && !($transporter instanceof TransporterInterface)
        ) {
            throw new \InvalidArgumentException(sprintf('Service\'s transporter must be instanceof %s.', 'JetTransporterInterface'));
        }
    }

    /**
     * @param mixed $registry
     * @throws \InvalidArgumentException
     */
    public static function assertRegistry($registry)
    {
        if (is_null($registry)) {
            return;
        }

        if (is_string($registry)) {
            if (!RegistryManager::isRegistered($registry)) {
                throw new \InvalidArgumentException(sprintf('REGISTRY %s does not registered yet.', $registry));
            }
        } elseif (!($registry instanceof RegistryInterface)) {
            throw new \InvalidArgumentException(sprintf('REGISTRY must be instanceof %s.', 'JetRegistryInterface'));
        }
    }

    /**
     * @param mixed $packer
     * @throws \InvalidArgumentException
     */
    public static function assertPacker($packer)
    {
        if (
            !is_null($packer)
            && !($packer instanceof PackerInterface)
        ) {
            throw new \InvalidArgumentException(sprintf('PACKER of service must be instanceof %s.', 'JetPackerInterface'));
        }
    }

    /**
     * @param mixed $dataFormatter
     * @throws \InvalidArgumentException
     */
    public static function assertDataFormatter($dataFormatter)
    {
        if (
            !is_null($dataFormatter)
            && !($dataFormatter instanceof DataFormatterInterface)
        ) {
            throw new \InvalidArgumentException(sprintf('DATA_FORMATTER of service must be instanceof %s.', 'JetDataFormatterInterface'));
        }
    }

    /**
     * @param mixed $pathGenerator
     * @throws \InvalidArgumentException
     */
    public static function assertPathGenerator($pathGenerator)
    {
        if (
            !is_null($pathGenerator)
            && !($pathGenerator instanceof PathGeneratorInterface)
        ) {
            throw new \InvalidArgumentException(sprintf('PATH_GENERATOR of service must be instanceof %s.', 'JetPathGeneratorInterface'));
        }
    }

    /**
     * @param mixed $tries
     * @throws \InvalidArgumentException
     */
    public static function assertTries($tries)
    {
        if (!is_null($tries) && !is_int($tries)) {
            throw new \InvalidArgumentException('TRIES of service must be int.');
        }
    }

    /**
     * @param mixed $timeout
     * @throws \InvalidArgumentException
     */
    public static function assertTimeout($timeout)
    {
        if (!is_null($timeout) && !is_int($timeout)) {
            throw new \InvalidArgumentException('TIMEOUT of service must be int.');
        }
    }

    /**
     * @param mixed $protocol
     * @throws \InvalidArgumentException
     */
    public static function assertProtocol($protocol)
    {
        if (
            !is_null($protocol) 
            && !is_string($protocol) 
            && !in_array($protocol, array('jsonrpc', 'jsonrpc-http'))
        ) {
            throw new \InvalidArgumentException('PROTOCOL of service must be jsonrpc or jsonrpc-http.');
        }
    }
}
