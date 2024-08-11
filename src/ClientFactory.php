<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

use Closure;
use Exception;
use InvalidArgumentException;

class ClientFactory
{
    /**
     * Create a client.
     * @param Closure|Metadata|string $service
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function create($service): Client
    {
        if ($service instanceof Metadata) {
            return new Client($service);
        }

        if (is_string($service)) {
            $metadata = ServiceManager::get($service) ?? new Metadata($service);
            return new Client($metadata);
        }

        if ($service instanceof Closure) {
            $metadata = $service();
            if ($metadata instanceof Metadata) {
                return new Client($metadata);
            }
        }

        throw new InvalidArgumentException('$service must been instanced of string/Closure/Metadata');
    }
}
