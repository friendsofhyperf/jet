<?php

namespace Jet\Contract;

interface JetPackerInterface
{
    public function pack($data);
    public function unpack($data);
}