<?php

$baseDir = realpath(__DIR__);
$classMap = array(
    'JetConsulporterInterface' => $baseDir . '/Contract/JetConsulporterInterface.php',
    'JetDataFormatterInterface' => $baseDir . '/Contract/JetDataFormatterInterface.php',
    'JetPackerInterface' => $baseDir . '/Contract/JetPackerInterface.php',
    'JetPathGeneratorInterface' => $baseDir . '/Contract/JetPathGeneratorInterface.php',
    'JetLoadBalancerInterface' => $baseDir . '/Contract/JetLoadBalancerInterface.php',
    'JetTransporterInterface' => $baseDir . '/Contract/JetTransporterInterface.php',
    'JetConsulporter' => $baseDir . '/Consulporter/JetConsulporter.php',
    'JetServiceManager' => $baseDir . '/JetServiceManager.php',
    'AbstractJetClient' => $baseDir . '/AbstractJetClient.php',
    'JetCurlHttpTransporter' => $baseDir . '/Transporter/JetCurlHttpTransporter.php',
    'JetStreamSocketTransporter' => $baseDir . '/Transporter/JetStreamSocketTransporter.php',
    'AbstractJetTransporter' => $baseDir . '/Transporter/AbstractJetTransporter.php',
    'JetRoundRobinLoadBalancer' => $baseDir . '/LoadBalancer/JetRoundRobinLoadBalancer.php',
    'AbstractJetLoadBalancer' => $baseDir . '/LoadBalancer/AbstractJetLoadBalancer.php',
    'JetRandomLoadBalancer' => $baseDir . '/LoadBalancer/JetRandomLoadBalancer.php',
    'JetLoadBalancerNode' => $baseDir . '/LoadBalancer/JetLoadBalancerNode.php',
    'JetClient' => $baseDir . '/JetClient.php',
    'bootstrap' => $baseDir . '/bootstrap.php',
    'JetPathGenerator' => $baseDir . '/PathGenerator/JetPathGenerator.php',
    'JetConsulClient' => $baseDir . '/Consul/JetConsulClient.php',
    'JetConsulHealth' => $baseDir . '/Consul/JetConsulHealth.php',
    'JetClientFactory' => $baseDir . '/JetClientFactory.php',
    'JetUtil' => $baseDir . '/JetUtil.php',
    'JetServerException' => $baseDir . '/Exception/JetServerException.php',
    'JetClientException' => $baseDir . '/Exception/JetClientException.php',
    'JetRecvFailedException' => $baseDir . '/Exception/JetRecvFailedException.php',
    'JetDataFormatter' => $baseDir . '/DataFormatter/JetDataFormatter.php',
    'JetJsonEofPacker' => $baseDir . '/Packer/JetJsonEofPacker.php',
    'JetJsonLengthPacker' => $baseDir . '/Packer/JetJsonLengthPacker.php',
);

spl_autoload_register(function ($class) use ($classMap) {
    if (isset($classMap[$class]) && is_file($classMap[$class])) {
        require_once $classMap[$class];
    }
});