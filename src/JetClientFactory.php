<?php

class JetClientFactory
{
    
    /**
     * Create a client
     *
     * @param string $service
     * @param JetTransporterInterface|string|int|null $transporter
     * @param JetPackerInterface|null $packer
     * @param JetDataFormatterInterface|null $dataFormatter
     * @param JetPathGeneratorInterface|null $pathGenerator
     * @param int|null $tries
     * @return JetClient
     * @throws JetClientException
     */
    public static function create($service, $transporter = null, $packer = null, $dataFormatter = null, $pathGenerator = null, $tries = null)
    {
        if (!$metadata = JetServiceManager::get($service)) {
            $metadata = new JetMetadata($service);

            if (JetRegistryManager::isRegistered(JetRegistryManager::DEFAULT_REGISTRY)) {
                $metadata->setRegistry(JetRegistryManager::get(JetRegistryManager::DEFAULT_REGISTRY));
            }

            if (is_numeric($transporter)) {
                $metadata->setTimeout($transporter);
            } elseif (is_string($transporter)) {
                $metadata->setProtocol($transporter);
            } elseif ($transporter instanceof JetTransporterInterface) {
                $metadata->setTransporter($transporter);
            }

            if ($packer instanceof JetPackerInterface) {
                $metadata->setPacker($packer);
            }

            if ($dataFormatter instanceof JetDataFormatterInterface) {
                $metadata->setDataFormatter($dataFormatter);
            }

            if ($pathGenerator instanceof JetPathGeneratorInterface) {
                $metadata->setPathGenerator($pathGenerator);
            }

            if (is_numeric($tries)) {
                $metadata->setTries($tries);
            }
        }

        return new JetClient($metadata);
    }
}
