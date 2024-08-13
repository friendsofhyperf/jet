<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

class JetClassLoader78077fc26b45d059afaed09e
{
    public static $registered = false;

    public static function register()
    {
        if (self::$registered) {
            return;
        }

        $baseDir = realpath(__DIR__);
        $classMap = array(
            'FriendsOfHyperf\Jet\ServiceManager' => $baseDir . '/ServiceManager.php',
            'FriendsOfHyperf\Jet\Contract\LoadBalancerInterface' => $baseDir . '/Contract/LoadBalancerInterface.php',
            'FriendsOfHyperf\Jet\Contract\DataFormatterInterface' => $baseDir . '/Contract/DataFormatterInterface.php',
            'FriendsOfHyperf\Jet\Contract\RegistryInterface' => $baseDir . '/Contract/RegistryInterface.php',
            'FriendsOfHyperf\Jet\Contract\PackerInterface' => $baseDir . '/Contract/PackerInterface.php',
            'FriendsOfHyperf\Jet\Contract\TransporterInterface' => $baseDir . '/Contract/TransporterInterface.php',
            'FriendsOfHyperf\Jet\Contract\PathGeneratorInterface' => $baseDir . '/Contract/PathGeneratorInterface.php',
            'FriendsOfHyperf\Jet\RegistryManager' => $baseDir . '/RegistryManager.php',
            'FriendsOfHyperf\Jet\Transporter\StreamSocketTransporter' => $baseDir . '/Transporter/StreamSocketTransporter.php',
            'FriendsOfHyperf\Jet\Transporter\AbstractTransporter' => $baseDir . '/Transporter/AbstractTransporter.php',
            'FriendsOfHyperf\Jet\Transporter\CurlHttpTransporter' => $baseDir . '/Transporter/CurlHttpTransporter.php',
            'FriendsOfHyperf\Jet\Metadata' => $baseDir . '/Metadata.php',
            'FriendsOfHyperf\Jet\LoadBalancer\AbstractLoadBalancer' => $baseDir . '/LoadBalancer/AbstractLoadBalancer.php',
            'FriendsOfHyperf\Jet\LoadBalancer\LoadBalancerNode' => $baseDir . '/LoadBalancer/LoadBalancerNode.php',
            'FriendsOfHyperf\Jet\LoadBalancer\RoundRobinLoadBalancer' => $baseDir . '/LoadBalancer/RoundRobinLoadBalancer.php',
            'FriendsOfHyperf\Jet\LoadBalancer\RandomLoadBalancer' => $baseDir . '/LoadBalancer/RandomLoadBalancer.php',
            'FriendsOfHyperf\Jet\ClientFactory' => $baseDir . '/ClientFactory.php',
            'FriendsOfHyperf\Jet\PathGenerator\PathGenerator' => $baseDir . '/PathGenerator/PathGenerator.php',
            'FriendsOfHyperf\Jet\PathGenerator\DotPathGenerator' => $baseDir . '/PathGenerator/DotPathGenerator.php',
            'FriendsOfHyperf\Jet\PathGenerator\FullPathGenerator' => $baseDir . '/PathGenerator/FullPathGenerator.php',
            'FriendsOfHyperf\Jet\Consul\Catalog' => $baseDir . '/Consul/Catalog.php',
            'FriendsOfHyperf\Jet\Consul\Agent' => $baseDir . '/Consul/Agent.php',
            'FriendsOfHyperf\Jet\Consul\Health' => $baseDir . '/Consul/Health.php',
            'FriendsOfHyperf\Jet\Consul\Response' => $baseDir . '/Consul/Response.php',
            'FriendsOfHyperf\Jet\Consul\Client' => $baseDir . '/Consul/Client.php',
            'FriendsOfHyperf\Jet\Support\Str' => $baseDir . '/Support/Str.php',
            'FriendsOfHyperf\Jet\Support\Assert' => $baseDir . '/Support/Assert.php',
            'FriendsOfHyperf\Jet\Support\Util' => $baseDir . '/Support/Util.php',
            'FriendsOfHyperf\Jet\Support\UserAgent' => $baseDir . '/Support/UserAgent.php',
            'FriendsOfHyperf\Jet\Support\Arr' => $baseDir . '/Support/Arr.php',
            'FriendsOfHyperf\Jet\Registry\ConsulRegistry' => $baseDir . '/Registry/ConsulRegistry.php',
            'FriendsOfHyperf\Jet\Exception\ServerException' => $baseDir . '/Exception/ServerException.php',
            'FriendsOfHyperf\Jet\Exception\ClientException' => $baseDir . '/Exception/ClientException.php',
            'FriendsOfHyperf\Jet\Exception\ExceptionThrower' => $baseDir . '/Exception/ExceptionThrower.php',
            'FriendsOfHyperf\Jet\Exception\ConnectionException' => $baseDir . '/Exception/ConnectionException.php',
            'FriendsOfHyperf\Jet\Exception\RecvFailedException' => $baseDir . '/Exception/RecvFailedException.php',
            'FriendsOfHyperf\Jet\Exception\Exception' => $baseDir . '/Exception/Exception.php',
            'FriendsOfHyperf\Jet\DataFormatter\DataFormatter' => $baseDir . '/DataFormatter/DataFormatter.php',
            'FriendsOfHyperf\Jet\DataFormatter\MultiplexDataFormatter' => $baseDir . '/DataFormatter/MultiplexDataFormatter.php',
            'FriendsOfHyperf\Jet\Client' => $baseDir . '/Client.php',
            'FriendsOfHyperf\Jet\Packer\JsonEofPacker' => $baseDir . '/Packer/JsonEofPacker.php',
            'FriendsOfHyperf\Jet\Packer\JsonLengthPacker' => $baseDir . '/Packer/JsonLengthPacker.php',
            'FriendsOfHyperf\Jet\Packer\JsonMultiplexPacker' => $baseDir . '/Packer/JsonMultiplexPacker.php',
            'FriendsOfHyperf\Jet\Facade' => $baseDir . '/Facade.php',
        );

        spl_autoload_register(function ($class) use ($classMap) {
            if (isset($classMap[$class]) && is_file($classMap[$class])) {
                require_once $classMap[$class];
            }
        });

        self::$registered = true;
    }
}

JetClassLoader78077fc26b45d059afaed09e::register();
