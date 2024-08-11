<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  Huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Packer;

use FriendsOfHyperf\Jet\Contract\PackerInterface;

class JsonEofPacker implements PackerInterface
{
    /**
     * @var string
     */
    protected $eof;

    public function __construct(string $eof = "\r\n")
    {
        $this->eof = $eof;
    }

    /**
     * @param mixed $data
     */
    public function pack($data): string
    {
        $data = json_encode($data);

        return $data . $this->eof;
    }

    /**
     * @return mixed
     */
    public function unpack(string $data)
    {
        return json_decode($data, true);
    }
}
