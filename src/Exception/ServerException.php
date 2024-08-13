<?php

/**
 * This file is part of friendsofhyperf/jet.
 * 
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Exception;

use FriendsOfHyperf\Jet\Support\Arr;

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
        $code = Arr::get($error, 'code', 0);
        $message = Arr::get($error, 'message', 'Server Error');

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
