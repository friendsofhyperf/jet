<?php

class JetServiceManager
{
    const REGISTRY       = 'rg';
    const TRANSPORTER    = 'tp';
    const PACKER         = 'pk';
    const DATA_FORMATTER = 'df';
    const PATH_GENERATOR = 'pg';
    const TRIES          = 'ts';

    /**
     * @var array
     */
    protected static $services = array();

    /**
     * @param string $service
     * @return array
     */
    public static function get($service)
    {
        return self::isRegistered($service) ? static::$services[$service] : array();
    }

    /**
     * @param string $service
     * @return bool
     */
    public static function isRegistered($service)
    {
        return isset(static::$services[$service]);
    }

    /**
     * @param string $service
     * @param JetMetadata $metadata
     * @return void
     * @throws InvalidArgumentException
     */
    public static function register($service, $metadata)
    {
        static::$services[$service] = $metadata;
    }

    /**
     * @param string $service
     * @param string $protocol
     * @return void
     */
    public static function deregister($service)
    {
        unset(static::$services[$service]);
    }

    /**
     * @param JetRegistryInterface $registry
     * @param bool $force
     * @return void
     * @throws InvalidArgumentException
     */
    public static function registerDefaultRegistry($registry, $force = false)
    {
        static::assertRegistry($registry);

        JetRegistryManager::register(JetRegistryManager::DEFAULT_REGISTRY, $registry, $force);
    }

    /**
     * @return null|JetRegistryInterface
     */
    public static function getDefaultRegistry()
    {
        return JetRegistryManager::get(JetRegistryManager::DEFAULT_REGISTRY);
    }

    /**
     * @param mixed $transporter
     * @throws InvalidArgumentException
     */
    public static function assertTransporter($transporter)
    {
        if (!is_null($transporter) && !($transporter instanceof JetTransporterInterface)) {
            throw new InvalidArgumentException(sprintf('Service\'s transporter must be instanceof %s.', 'JetTransporterInterface'));
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
            if (!JetRegistryManager::isRegistered($registry)) {
                throw new InvalidArgumentException(sprintf('REGISTRY %s does not registered yet.', $registry));
            }
        } elseif (!($registry instanceof JetRegistryInterface)) {
            throw new InvalidArgumentException(sprintf('REGISTRY must be instanceof %s.', 'JetRegistryInterface'));
        }
    }

    /**
     * @param mixed $packer
     * @throws InvalidArgumentException
     */
    public static function assertPacker($packer)
    {
        if (!is_null($packer) && !($packer instanceof JetPackerInterface)) {
            throw new InvalidArgumentException(sprintf('PACKER of service must be instanceof %s.', 'JetPackerInterface'));
        }
    }

    /**
     * @param mixed $dataFormatter
     * @throws InvalidArgumentException
     */
    public static function assertDataFormatter($dataFormatter)
    {
        if (!is_null($dataFormatter) && !($dataFormatter instanceof JetDataFormatterInterface)) {
            throw new InvalidArgumentException(sprintf('DATA_FORMATTER of service must be instanceof %s.', 'JetDataFormatterInterface'));
        }
    }

    /**
     * @param mixed $pathGenerator
     * @throws InvalidArgumentException
     */
    public static function assertPathGenerator($pathGenerator)
    {
        if (!is_null($pathGenerator) && !($pathGenerator instanceof JetPathGeneratorInterface)) {
            throw new InvalidArgumentException(sprintf('PATH_GENERATOR of service must be instanceof %s.', 'JetPathGeneratorInterface'));
        }
    }

    /**
     * @param mixed $tries
     * @throws InvalidArgumentException
     */
    public static function assertTries($tries)
    {
        if (!is_null($tries) && !is_int($tries)) {
            throw new InvalidArgumentException('TRIES of service must be int.');
        }
    }

    /**
     * @param mixed $timeout
     * @throws InvalidArgumentException
     */
    public static function assertTimeout($timeout)
    {
        if (!is_null($timeout) && !is_int($timeout)) {
            throw new InvalidArgumentException('TIMEOUT of service must be int.');
        }
    }

    /**
     * @param mixed $protocol
     * @throws InvalidArgumentException
     */
    public static function assertProtocol($protocol)
    {
        if (!is_null($protocol) && !is_string($protocol) && !in_array($protocol, array('jsonrpc', 'jsonrpc-http'))) {
            throw new InvalidArgumentException('PROTOCOL of service must be jsonrpc or jsonrpc-http.');
        }
    }
}
