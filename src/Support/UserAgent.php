<?php

/*
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Support;

use FriendsOfHyperf\Jet\Client;

class UserAgent
{
    /**
     * @var string
     */
    protected static $value = null;

    /**
     * @param string $value
     * @return void
     */
    public static function set($value)
    {
        self::$value = $value;
    }

    /**
     * @return string
     */
    public static function get()
    {
        if (! is_null(self::$value)) {
            return self::$value;
        }

        $version = curl_version();

        return self::$value = sprintf(
            'jet/%s php/%s curl/%s',
            Client::MAJOR_VERSION,
            PHP_VERSION,
            $version['version']
        );
    }
}
