<?php

namespace FriendsOfHyperf\Jet\PathGenerator;

use FriendsOfHyperf\Jet\Contract\PathGeneratorInterface;
use FriendsOfHyperf\Jet\Support\Str;

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
        $handledNamespace = Str::replaceArray('\\', array('/'), end($handledNamespace));
        $path = Str::studly($handledNamespace);

        return $path . '.' . Str::studly($method);
    }
}
