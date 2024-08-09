<?php

namespace Jet\Exception;

use Jet\Util;

class ServerException extends Exception
{
    /**
     * @var array
     */
    protected $error;

    /**
     * @param array $error
     * @param Exception|null $previous
     * @return void
     */
    public function __construct($error = array(), $previous = null)
    {
        $code = Util::arrayGet($error, 'code', 0);
        $message = Util::arrayGet($error, 'message', 'Server Error');

        $this->error = $error;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}
