<?php

class JetDotPathGenerator implements JetPathGeneratorInterface
{
    /**
     * @param string $service
     * @param string $method
     * @return string
     */
    public function generate($service, $method)
    {
        $handledNamespace = explode('\\', $service);
        $handledNamespace = JetUtil::replaceArray('\\', ['/'], end($handledNamespace));
        $path             = JetUtil::studly($handledNamespace);

        return $path . '.' . JetUtil::studly($method);
    }
}
