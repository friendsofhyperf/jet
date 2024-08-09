<?php

namespace FriendsOfHyperf\Jet\Contract;

interface PackerInterface
{
    public function pack($data);
    public function unpack($data);
}