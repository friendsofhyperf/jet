<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Tests;

use FriendsOfHyperf\Jet\ClientFactory;
use FriendsOfHyperf\Jet\Metadata;
use FriendsOfHyperf\Jet\RegistryManager;

/**
 * @internal
 * @coversNothing
 */
class ClientTest extends TestCase
{
    private $service = 'CalculatorService';

    // public function testCalculatorServiceByRegistry()
    // {
    //     $registry = $this->createRegistry();

    //     RegistryManager::register(RegistryManager::DEFAULT, $registry, true);

    //     $client = ClientFactory::create($this->service, 'jsonrpc-http');

    //     $a = rand(1, 99);
    //     $b = rand(1, 99);

    //     $this->assertSame($a + $b, $client->add($a, $b));

    //     $client = ClientFactory::create($this->service, 'jsonrpc');

    //     $a = rand(1, 99);
    //     $b = rand(1, 99);

    //     $this->assertSame($a + $b, $client->add($a, $b));
    // }

    public function testCalculatorServiceByGuzzleHttpTransporter()
    {
        $client = ClientFactory::create(function () {
            return tap(new Metadata($this->service), function (Metadata $metadata) {
                $metadata->setTransporter($this->createGuzzleHttpTransporter());
            });
        });

        $a = rand(1, 99);
        $b = rand(1, 99);

        $this->assertSame($a + $b, $client->add($a, $b));
    }

    public function testCalculatorServiceByStreamSocketTransporter()
    {
        $client = ClientFactory::create(function () {
            return tap(new Metadata($this->service), function (Metadata $metadata) {
                $metadata->setTransporter($this->createStreamSocketTransporter());
            });
        });

        $a = rand(1, 99);
        $b = rand(1, 99);

        $this->assertSame($a + $b, $client->add($a, $b));
    }
}
