<?php

namespace Jet\PathGenerator;

use Jet\Contract\JetPathGeneratorInterface;

class JetFullPathGenerator implements JetPathGeneratorInterface
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
