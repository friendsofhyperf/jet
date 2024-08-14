<?php

/*
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
    protected static $metadata = array();

    /**
     * @param string $name
     * @param Metadata $metadata
     * @return void
     */
    public static function register($name, $metadata)
    {
        static::$metadata[$name] = $metadata;
    }

    /**
     * @param string $name
     * @return null|Metadata
     */
    public static function get($name)
    {
        return isset(static::$metadata[$name]) ? clone static::$metadata[$name] : null;
    }
}
