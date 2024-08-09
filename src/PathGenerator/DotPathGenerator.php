<?php

namespace Jet\PathGenerator;

use Jet\Contract\PathGeneratorInterface;
use Jet\Util;

class DotPathGenerator implements PathGeneratorInterface
{
    /**
     * @param string $service
     * @param string $method
     * @return string
     */
    public function generate($service, $method)
    {
        $handledNamespace = explode('\\', $service);
        $handledNamespace = Util::replaceArray('\\', ['/'], end($handledNamespace));
        $path = Util::studly($handledNamespace);

        return $path . '.' . Util::studly($method);
    }
}
