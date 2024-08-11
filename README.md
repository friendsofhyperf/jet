# Jet

[![Latest Test](https://github.com/friendsofhyperf/jet/workflows/tests-1.x/badge.svg)](https://github.com/friendsofhyperf/jet/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/friendsofhyperf/jet)](https://packagist.org/packages/friendsofhyperf/jet)
[![Total Downloads](https://img.shields.io/packagist/dt/friendsofhyperf/jet)](https://packagist.org/packages/friendsofhyperf/jet)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/jet)](https://github.com/friendsofhyperf/jet)

Another jet client for Hyperf (PHP5.3)

## Installation

### Require

```php
require 'path/jet-client/bootstrap.php';
```

### Composer

```shell
composer require "friendsofhyperf/jet:^1.3"
```

## QuickStart

### Register with metadata

```php
use FriendsOfHyperf\Jet\Metadata;
use FriendsOfHyperf\Jet\Transporter\CurlHttpTransporter;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;
use FriendsOfHyperf\Jet\ServiceManager;

$metadata = (new Metadata('Name'))
    ->setTransporter(new CurlHttpTransporter('127.0.0.1', 9502))
    ->setRegistry(new ConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));

ServiceManager::register('CalculatorService', $metadata);
```

### Register default registry

```php
RegistryManager::register(RegistryManager::DEFAULT_REGISTRY, new ConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));
```

## Call RPC method

### Call by ClientFactory

```php
use FriendsOfHyperf\Jet\ClientFactory;

$client = ClientFactory::create('CalculatorService');
var_dump($client->add(1, 20));
```

### Call by custom client

```php
use FriendsOfHyperf\Jet\Client;
use FriendsOfHyperf\Jet\Metadata;
use FriendsOfHyperf\Jet\Transporter\CurlHttpTransporter;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

/**
 * @method int add(int $a, int $b)
 */
class CalculatorService extends Client
{
    public function __construct()
    {
        $metadata = (new Metadata('CalculatorService'))
            ->setTransporter(new CurlHttpTransporter('127.0.0.1', 9502))
            ->setRegistry(new ConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));

        parent::__construct($metadata);
    }
}

$service = new CalculatorService;
var_dump($service->add(3, 10));
```

### Call by custom facade

```php
use FriendsOfHyperf\Jet\Facade;

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
```
