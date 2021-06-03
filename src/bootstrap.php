<?php

$baseDir = realpath(__DIR__);
$classMap = array(
    'JetRegistryInterface' => $baseDir . '/Contract/JetRegistryInterface.php',
    'JetDataFormatterInterface' => $baseDir . '/Contract/JetDataFormatterInterface.php',
    'JetPackerInterface' => $baseDir . '/Contract/JetPackerInterface.php',
    'JetPathGeneratorInterface' => $baseDir . '/Contract/JetPathGeneratorInterface.php',
    'JetLoadBalancerInterface' => $baseDir . '/Contract/JetLoadBalancerInterface.php',
    'JetTransporterInterface' => $baseDir . '/Contract/JetTransporterInterface.php',
    'JetServiceManager' => $baseDir . '/JetServiceManager.php',
    'JetRegistryManager' => $baseDir . '/JetRegistryManager.php',
    'JetCurlHttpTransporter' => $baseDir . '/Transporter/JetCurlHttpTransporter.php',
    'JetStreamSocketTransporter' => $baseDir . '/Transporter/JetStreamSocketTransporter.php',
    'AbstractJetTransporter' => $baseDir . '/Transporter/AbstractJetTransporter.php',
    'JetFacade' => $baseDir . '/JetFacade.php',
    'JetRoundRobinLoadBalancer' => $baseDir . '/LoadBalancer/JetRoundRobinLoadBalancer.php',
    'AbstractJetLoadBalancer' => $baseDir . '/LoadBalancer/AbstractJetLoadBalancer.php',
    'JetRandomLoadBalancer' => $baseDir . '/LoadBalancer/JetRandomLoadBalancer.php',
    'JetLoadBalancerNode' => $baseDir . '/LoadBalancer/JetLoadBalancerNode.php',
    'JetClient' => $baseDir . '/JetClient.php',
    'JetMetadata' => $baseDir . '/JetMetadata.php',
    'JetPathGenerator' => $baseDir . '/PathGenerator/JetPathGenerator.php',
    'JetDotPathGenerator' => $baseDir . '/PathGenerator/JetDotPathGenerator.php',
    'JetFullPathGenerator' => $baseDir . '/PathGenerator/JetFullPathGenerator.php',
    'JetConsulCatalog' => $baseDir . '/Consul/JetConsulCatalog.php',
    'JetConsulClient' => $baseDir . '/Consul/JetConsulClient.php',
    'JetConsulResponse' => $baseDir . '/Consul/JetConsulResponse.php',
    'JetConsulHealth' => $baseDir . '/Consul/JetConsulHealth.php',
    'JetConsulAgent' => $baseDir . '/Consul/JetConsulAgent.php',
    'JetClientFactory' => $baseDir . '/JetClientFactory.php',
    'JetConsulRegistry' => $baseDir . '/Registry/JetConsulRegistry.php',
    'JetUtil' => $baseDir . '/JetUtil.php',
    'JetServerException' => $baseDir . '/Exception/JetServerException.php',
    'JetClientException' => $baseDir . '/Exception/JetClientException.php',
    'JetException' => $baseDir . '/Exception/JetException.php',
    'JetExceptionThrower' => $baseDir . '/Exception/JetExceptionThrower.php',
    'JetRecvFailedException' => $baseDir . '/Exception/JetRecvFailedException.php',
    'JetConnectionException' => $baseDir . '/Exception/JetConnectionException.php',
    'JetDataFormatter' => $baseDir . '/DataFormatter/JetDataFormatter.php',
    'JetJsonEofPacker' => $baseDir . '/Packer/JetJsonEofPacker.php',
    'JetJsonLengthPacker' => $baseDir . '/Packer/JetJsonLengthPacker.php',
);

spl_autoload_register(function ($class) use ($classMap) {
    if (isset($classMap[$class]) && is_file($classMap[$class])) {
        require_once $classMap[$class];
    }
});
