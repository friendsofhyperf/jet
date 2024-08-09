<?php

namespace FriendsOfHyperf\Jet\Packer;

use FriendsOfHyperf\Jet\Contract\PackerInterface;

class JsonMultiplexPacker implements PackerInterface
{
    public function __construct()
    {
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function pack($data)
    {
        return sprintf(
            '%s%s%s',
            pack('N', strlen($data) + 4),
            pack('N', time()),
            $data
        );

    }

    /**
     * @param string $data
     * @return array
     */
    public function unpack($data)
    {
        $unpacked = unpack('Nid', substr($data, 4, 4));
        $body = substr($data, 8);
        return [$unpacked['id'], $body];
    }
}
