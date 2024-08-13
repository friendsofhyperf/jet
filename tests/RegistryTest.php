<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

require_once __DIR__ . '/../src/autoload.php';

use FriendsOfHyperf\Jet\Consul\Catalog;
use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;
use FriendsOfHyperf\Jet\RegistryManager;
use FriendsOfHyperf\Jet\Support\Arr;

$configFile = is_file(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.php.dist';
$configs = include $configFile;

$uri = Arr::get($configs, 'consul.uri', 'http://127.0.0.1:8500');

echo sprintf("CONSUL_URI: %s\n", $uri);

$catalog = new Catalog(array(
    'uri' => $uri,
));

echo "Test get services by Catalog\n";
$services = $catalog->services()->json();
var_dump($services);

echo "Test get services by JetConsulRegistry\n";
$registry = new ConsulRegistry(array('uri' => $uri));
$services = $registry->getServices();
var_dump($services);

echo "Test get service nodes\n";
$nodes = $registry->getServiceNodes($service = 'CalculatorService');
var_dump($nodes);

echo "Test JetRegistryManager::register()\n";
RegistryManager::register(RegistryManager::DEFAULT_REGISTRY, $registry, true);
var_dump(RegistryManager::get(RegistryManager::DEFAULT_REGISTRY) instanceof RegistryInterface);
