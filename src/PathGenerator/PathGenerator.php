<?php

namespace Jet\PathGenerator;

use Jet\Contract\PathGeneratorInterface;
use Jet\Util as JetUtil;

class PathGenerator implements PathGeneratorInterface
{
    /**
     * @param string $service 
     * @param string $method 
     * @return string 
     */
    public function generate($service, $method)
    {
        $handledNamespace = explode('\\', $service);
        $handledNamespace = str_replace('\\', '/', end($handledNamespace));
        $handledNamespace = str_replace('Service', '', $handledNamespace);
        $path = JetUtil::snake($handledNamespace);

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $path . '/' . $method;
    }
}