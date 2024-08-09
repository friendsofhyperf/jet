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
composer require "friendsofhyperf/jet:^1.0"
```

## QuickStart

### Register with metadata

```php
$metadata = (new JetMetadata('Name'))
    ->setTransporter(new JetCurlHttpTransporter('127.0.0.1', 9502))
    ->setRegistry(new JetConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));

JetServiceManager::register('CalculatorService', $metadata);
```

### Register default registry

```php
JetRegistryManager::register(JetRegistryManager::DEFAULT_REGISTRY, new JetConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));
```

## Call RPC method

### Call by JetClientFactory

```php
$client = JetClientFactory::create('CalculatorService');
var_dump($client->add(1, 20));
```

### Call by custom client

```php
/**
 * @method int add(int $a, int $b)
 */
class CalculatorService extends JetClient
{
    public function __construct()
    {
        $metadata = (new JetMetadata('CalculatorService'))
            ->setTransporter(new JetCurlHttpTransporter('127.0.0.1', 9502))
            ->setRegistry(new JetConsulRegistry(array('uri' => 'http://127.0.0.1:8500')));

        parent::__construct($metadata);
    }
}

$service = new CalculatorService;
var_dump($service->add(3, 10));
```

### Call by custom facade

```php
/**
 * @method static int add(int $a, int $b)
 */
class Calculator extends JetFacade
{
    protected static function getFacadeAccessor()
    {
        // return JetClientFactory::create('CalculatorService');
        return 'CalculatorService';
    }
}

var_dump(Calculator::add(rand(0, 100), rand(0, 100)));
```
