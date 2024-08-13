<?php

/*
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Contract;

interface PackerInterface
{
    public function pack($data);
    public function unpack($data);
}