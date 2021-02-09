# Jet

[![Latest Test](https://github.com/friendsofhyperf/jet/workflows/tests/badge.svg)](https://github.com/friendsofhyperf/jet/actions)
[![Latest Stable Version](https://poser.pugx.org/friendsofhyperf/jet/version.png)](https://packagist.org/packages/friendsofhyperf/jet)
[![Total Downloads](https://poser.pugx.org/friendsofhyperf/jet/d/total.png)](https://packagist.org/packages/friendsofhyperf/jet)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/jet)](https://github.com/friendsofhyperf/jet)

Another jet client for Hyperf

## Installation

### Composer

~~~php
composer require "friendsofhyperf/jet:^2.0"
~~~

## Quickstart

### Register with metadata

~~~php
use FriendsOfHyperf\Jet\ServiceManager;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;

ServiceManager::register('CalculatorService', [
    // register with transporter
    ServiceManager::TRANSPORTER => new GuzzleHttpTransporter('127.0.0.1', 9502),
    // or register with registry
    ServiceManager::REGISTRY => new ConsulRegistry(['uri' => 'http://127.0.0.1:8500']),
]);
~~~

### Auto register services by registry

~~~php
use FriendsOfHyperf\Jet\ServiceManager;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

$registry = new ConsulRegistry(['uri' => 'http://127.0.0.1:8500']);
$registry->register('CalculatorService'); // register a service
$registry->register(['CalculatorService', 'CalculatorService2']); // register some services
$registry->register(); // register all service
~~~

### Register default registry

~~~php
use FriendsOfHyperf\Jet\RegistryManager;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

RegistryManager::register(RegistryManager::DEFAULT, new ConsulRegistry(['uri' => $uri, 'timeout' => 1]));
~~~

> In Laravel project, Add to `boot()` in `App/Providers/AppServiceProvider.php`

## Call RPC method

### Call by ClientFactory

~~~php
use FriendsOfHyperf\Jet\ClientFactory;

$client = ClientFactory::create('CalculatorService');
var_dump($client->add(1, 20));
~~~

### Call by custom client

~~~php
use FriendsOfHyperf\Jet\Client;
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

/**
 * @method int add(int $a, int $b)
 */
class CalculatorService extends Client
{
    public function __construct($service = 'CalculatorService', $transporter = null, $packer = null, $dataFormatter = null, $pathGenerator = null, $tries = null)
    {
        // Custom transporter
        $transporter = new GuzzleHttpTransporter('127.0.0.1', 9502);

        // Or get tranporter by registry
        $registry    = new ConsulRegistry(['uri' => 'http://127.0.0.1:8500']);
        $transporter = $registry->getTransporter($service);

        parent::__construct($service, $transporter, $packer, $dataFormatter, $pathGenerator, $tries);
    }
}

$service = new CalculatorService;
var_dump($service->add(3, 10));
~~~

### Call by custom facade

~~~php
use FriendsOfHyperf\Jet\Facade;
use FriendsOfHyperf\Jet\ClientFactory;

/**
 * @method static int add(int $a, int $b)
 */
class Calculator extends Facade
{
    protected static function getFacadeAccessor()
    {
        // return ClientFactory::create('CalculatorService');
        return 'CalculatorService';
    }
}

var_dump(Calculator::add(rand(0, 100), rand(0, 100)));
~~~
