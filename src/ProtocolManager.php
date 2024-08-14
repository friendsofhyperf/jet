<?php

namespace FriendsOfHyperf\Jet;

class ProtocolManager
{
    /**
     * @var array<string, Metadata>
     */
    protected static $protocols = array();

    /**
     * @param string $protocol
     * @param Metadata $metadata
     * @return void
     */
    public static function register($protocol, $metadata)
    {
        static::$protocols[$protocol] = $metadata;
    }

    /**
     * @param string $protocol
     * @return null|Metadata
     */
    public static function get($protocol)
    {
        return isset(static::$protocols[$protocol]) ? clone static::$protocols[$protocol] : null;
    }

    /**
     * @param string $name
     * @param string $protocol
     * @throws \RuntimeException
     * @return Client
     */
    public static function getService($name, $protocol = 'jsonrpc')
    {
        $metadata = self::get($protocol);

        if ($metadata === null) {
            throw new \RuntimeException(sprintf('Protocol %s undefined.', $protocol));
        }

        return new Client($metadata->withName($name));
    }
}
