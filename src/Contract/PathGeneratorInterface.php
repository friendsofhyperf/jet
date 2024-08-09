<?php

namespace FriendsOfHyperf\Jet\Contract;

interface PathGeneratorInterface
{
    /**
     * @param string $service 
     * @param string $method 
     * @return string 
     */
    public function generate($service, $method);
}