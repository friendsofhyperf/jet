<?php

/**
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Packer;

use FriendsOfHyperf\Jet\Contract\PackerInterface;

class JsonMultiplexPacker implements PackerInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function pack($data)
    {
        $data = json_encode($data);
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
        // $unpacked = unpack('Nid', substr($data, 4, 4));
        $body = substr($data, 8);
        return json_decode($body, true);
    }
}
