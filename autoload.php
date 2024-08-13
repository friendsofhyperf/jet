<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

class JetClassLoader9f6711fafbeec8e151d4f616
{
    static $registered = false;

    public static function register()
    {
        if (self::$registered) {
            return;
        }

        $baseDir = realpath(__DIR__);
        $classMap = array(
            'FriendsOfHyperf\Jet\src\ServiceManager' => $baseDir . '/src/ServiceManager.php',
            'FriendsOfHyperf\Jet\src\Contract\LoadBalancerInterface' => $baseDir . '/src/Contract/LoadBalancerInterface.php',
            'FriendsOfHyperf\Jet\src\Contract\DataFormatterInterface' => $baseDir . '/src/Contract/DataFormatterInterface.php',
            'FriendsOfHyperf\Jet\src\Contract\RegistryInterface' => $baseDir . '/src/Contract/RegistryInterface.php',
            'FriendsOfHyperf\Jet\src\Contract\PackerInterface' => $baseDir . '/src/Contract/PackerInterface.php',
            'FriendsOfHyperf\Jet\src\Contract\TransporterInterface' => $baseDir . '/src/Contract/TransporterInterface.php',
            'FriendsOfHyperf\Jet\src\Contract\PathGeneratorInterface' => $baseDir . '/src/Contract/PathGeneratorInterface.php',
            'FriendsOfHyperf\Jet\src\RegistryManager' => $baseDir . '/src/RegistryManager.php',
            'FriendsOfHyperf\Jet\src\Transporter\StreamSocketTransporter' => $baseDir . '/src/Transporter/StreamSocketTransporter.php',
            'FriendsOfHyperf\Jet\src\Transporter\AbstractTransporter' => $baseDir . '/src/Transporter/AbstractTransporter.php',
            'FriendsOfHyperf\Jet\src\Transporter\CurlHttpTransporter' => $baseDir . '/src/Transporter/CurlHttpTransporter.php',
            'FriendsOfHyperf\Jet\src\Metadata' => $baseDir . '/src/Metadata.php',
            'FriendsOfHyperf\Jet\src\LoadBalancer\AbstractLoadBalancer' => $baseDir . '/src/LoadBalancer/AbstractLoadBalancer.php',
            'FriendsOfHyperf\Jet\src\LoadBalancer\LoadBalancerNode' => $baseDir . '/src/LoadBalancer/LoadBalancerNode.php',
            'FriendsOfHyperf\Jet\src\LoadBalancer\RoundRobinLoadBalancer' => $baseDir . '/src/LoadBalancer/RoundRobinLoadBalancer.php',
            'FriendsOfHyperf\Jet\src\LoadBalancer\RandomLoadBalancer' => $baseDir . '/src/LoadBalancer/RandomLoadBalancer.php',
            'FriendsOfHyperf\Jet\src\ClientFactory' => $baseDir . '/src/ClientFactory.php',
            'FriendsOfHyperf\Jet\src\PathGenerator\PathGenerator' => $baseDir . '/src/PathGenerator/PathGenerator.php',
            'FriendsOfHyperf\Jet\src\PathGenerator\DotPathGenerator' => $baseDir . '/src/PathGenerator/DotPathGenerator.php',
            'FriendsOfHyperf\Jet\src\PathGenerator\FullPathGenerator' => $baseDir . '/src/PathGenerator/FullPathGenerator.php',
            'FriendsOfHyperf\Jet\src\Consul\Catalog' => $baseDir . '/src/Consul/Catalog.php',
            'FriendsOfHyperf\Jet\src\Consul\Agent' => $baseDir . '/src/Consul/Agent.php',
            'FriendsOfHyperf\Jet\src\Consul\Health' => $baseDir . '/src/Consul/Health.php',
            'FriendsOfHyperf\Jet\src\Consul\Response' => $baseDir . '/src/Consul/Response.php',
            'FriendsOfHyperf\Jet\src\Consul\Client' => $baseDir . '/src/Consul/Client.php',
            'FriendsOfHyperf\Jet\src\Support\Str' => $baseDir . '/src/Support/Str.php',
            'FriendsOfHyperf\Jet\src\Support\Assert' => $baseDir . '/src/Support/Assert.php',
            'FriendsOfHyperf\Jet\src\Support\Util' => $baseDir . '/src/Support/Util.php',
            'FriendsOfHyperf\Jet\src\Support\UserAgent' => $baseDir . '/src/Support/UserAgent.php',
            'FriendsOfHyperf\Jet\src\Support\Arr' => $baseDir . '/src/Support/Arr.php',
            'FriendsOfHyperf\Jet\src\Registry\ConsulRegistry' => $baseDir . '/src/Registry/ConsulRegistry.php',
            'FriendsOfHyperf\Jet\src\Exception\ServerException' => $baseDir . '/src/Exception/ServerException.php',
            'FriendsOfHyperf\Jet\src\Exception\ClientException' => $baseDir . '/src/Exception/ClientException.php',
            'FriendsOfHyperf\Jet\src\Exception\ExceptionThrower' => $baseDir . '/src/Exception/ExceptionThrower.php',
            'FriendsOfHyperf\Jet\src\Exception\ConnectionException' => $baseDir . '/src/Exception/ConnectionException.php',
            'FriendsOfHyperf\Jet\src\Exception\RecvFailedException' => $baseDir . '/src/Exception/RecvFailedException.php',
            'FriendsOfHyperf\Jet\src\Exception\Exception' => $baseDir . '/src/Exception/Exception.php',
            'FriendsOfHyperf\Jet\src\DataFormatter\DataFormatter' => $baseDir . '/src/DataFormatter/DataFormatter.php',
            'FriendsOfHyperf\Jet\src\DataFormatter\MultiplexDataFormatter' => $baseDir . '/src/DataFormatter/MultiplexDataFormatter.php',
            'FriendsOfHyperf\Jet\src\Client' => $baseDir . '/src/Client.php',
            'FriendsOfHyperf\Jet\src\Packer\JsonEofPacker' => $baseDir . '/src/Packer/JsonEofPacker.php',
            'FriendsOfHyperf\Jet\src\Packer\JsonLengthPacker' => $baseDir . '/src/Packer/JsonLengthPacker.php',
            'FriendsOfHyperf\Jet\src\Packer\JsonMultiplexPacker' => $baseDir . '/src/Packer/JsonMultiplexPacker.php',
            'FriendsOfHyperf\Jet\src\Facade' => $baseDir . '/src/Facade.php',
        );

        spl_autoload_register(function ($class) use ($classMap) {
            if (isset($classMap[$class]) && is_file($classMap[$class])) {
                require_once $classMap[$class];
            }
        });

        self::$registered = true;
    }
}

JetClassLoader9f6711fafbeec8e151d4f616::register();
