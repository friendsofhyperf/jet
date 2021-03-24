<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
 */
namespace FriendsOfHyperf\Jet\Tests;

use FriendsOfHyperf\Jet\Contract\RegistryInterface;
use FriendsOfHyperf\Jet\RegistryManager;

/**
 * @internal
 * @coversNothing
 */
class RegistryTest extends TestCase
{
    public function testGetServices()
    {
        $registry = $this->createRegistry();
        $services = $registry->getServices();

        $this->assertIsArray($services);
        $this->assertContains('consul', $services);
    }

    public function testRegistryManager()
    {
        $registry = $this->createRegistry();

        RegistryManager::register(RegistryManager::DEFAULT, $registry, true);

        $this->assertInstanceOf(RegistryInterface::class, RegistryManager::get(RegistryManager::DEFAULT));
    }
}
