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

class MetadataManager
{
    /**
     * @var array<string, Metadata>
     */
    protected static $metadata = [];

    public static function register(string $name, Metadata $metadata)
    {
        static::$metadata[$name] = $metadata;
    }

    /**
     * @return Metadata|null
     */
    public static function get(string $name)
    {
        return isset(static::$metadata[$name]) ? clone static::$metadata[$name] : null;
    }
}
