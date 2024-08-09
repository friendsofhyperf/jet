<?php

namespace FriendsOfHyperf\Jet\PathGenerator;

use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;

class FullPathGenerator implements PathGeneratorInterface
{
    /**
     * @param string $service
     * @param string $method
     * @return string
     */
    public function generate($service, $method)
    {
        $path = str_replace('\\', '/', $service);

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $path . '/' . $method;
    }
}
