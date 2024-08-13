<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Packer;

use FriendsOfHyperf\Jet\Contract\PackerInterface;

class JsonEofPacker implements PackerInterface
{
    /**
     * @var string
     */
    protected $eof;

    public function __construct($eof = "\r\n")
    {
        $this->eof = $eof;
    }

    /**
     * @param mixed $data 
     * @return string 
     */
    public function pack($data)
    {
        $data = json_encode($data);

        return $data . $this->eof;
    }

    /**
     * @param string $data
     * @return array
     */
    public function unpack($data)
    {
        return json_decode($data, true);
    }
}