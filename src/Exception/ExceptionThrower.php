<?php

namespace Jet\Exception;

final class ExceptionThrower
{
    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception 
     */
    public function getThrowable()
    {
        return $this->exception;
    }
}