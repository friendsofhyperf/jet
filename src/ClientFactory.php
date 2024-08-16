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
     * @param (Closure(): Metadata)|Metadata|string|mixed $service
     * @param Metadata|string|null $metadata
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function create($service, $metadata = null): Client
    {
        return match (true) {
            $service instanceof Metadata => new Client($service),
            $service instanceof Closure => new Client($service()),
            is_string($service) => match (true) {
                is_string($metadata) => new Client(MetadataManager::get($metadata)->withName($service)),
                $metadata instanceof Metadata => new Client($metadata->withName($service)),
                default => new Client(ServiceManager::get($service) ?? new Metadata($service)),
            },
            default => throw new InvalidArgumentException('$service must been instanced of string/Closure/Metadata'),
        };
    }
}
