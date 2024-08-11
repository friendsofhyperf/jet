# Jet

[![Latest Test](https://github.com/friendsofhyperf/jet/workflows/tests/badge.svg)](https://github.com/friendsofhyperf/jet/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/friendsofhyperf/jet)](https://packagist.org/packages/friendsofhyperf/jet)
[![Total Downloads](https://img.shields.io/packagist/dt/friendsofhyperf/jet)](https://packagist.org/packages/friendsofhyperf/jet)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/jet)](https://github.com/friendsofhyperf/jet)

Another jet client for Hyperf

## Installation

### Composer

```php
composer require "friendsofhyperf/jet:^2.0"
```

## QuickStart

### Register with metadata

```php
use FriendsOfHyperf\Jet\Metadata;
use FriendsOfHyperf\Jet\ServiceManager;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;

$metadata = new Metadata('CalculatorService');
$metadata->setTransporter(new GuzzleHttpTransporter('127.0.0.1', 9502));
$metadata->setRegistry(new ConsulRegistry(['uri' => 'http://127.0.0.1:8500']));

ServiceManager::register('CalculatorService', $metadata);
```

### Register default registry

```php
use FriendsOfHyperf\Jet\RegistryManager;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

RegistryManager::register(RegistryManager::DEFAULT, new ConsulRegistry(['uri' => $uri, 'timeout' => 1]));
```

> In Laravel project, Add to `boot()` in `App/Providers/AppServiceProvider.php`

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
use FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter;
use FriendsOfHyperf\Jet\Registry\ConsulRegistry;

/**
 * @method int add(int $a, int $b)
 */
class CalculatorService extends Client
{
    public function __construct($service = 'CalculatorService')
    {
        $metadata = new Metadata($service);

        // Custom transporter
        $metadata->setTransporter(new GuzzleHttpTransporter('127.0.0.1', 9502));

        // Custom registry
        $metadata->setRegistry(new ConsulRegistry(['uri' => 'http://127.0.0.1:8500']));

        parent::__construct($metadata);
    }
}

$service = new CalculatorService;
var_dump($service->add(3, 10));
```

### Call by custom facade

```php
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
```

## Coroutine support in Hyperf

- Aspect

```php
<?php

namespace App\Aspect;

use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Guzzle\ClientFactory;

class GuzzleHttpTransporterAspect extends AbstractAspect
{
    public array $classes = [
        'FriendsOfHyperf\Jet\Transporter\GuzzleHttpTransporter::getClient',
    ];

    protected ClientFactory $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $instance = $proceedingJoinPoint->getInstance();
        $config = (function () { return $this->config; })->call($instance);

        return $this->clientFactory->create($config);
    }
}
```

- Config `config/autoload/aspects.php`

```php
<?php

declare(strict_types=1);

return [
    'App\Aspect\GuzzleHttpTransporterAspect',
];
```
