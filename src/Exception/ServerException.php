<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/4.x/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet\Exception;

use Throwable;

class ServerException extends JetException
{
    /**
     * @var array
     */
    protected $error;

    public function __construct(array $error = [], Throwable $previous = null)
    {
        $code = $error['code'] ?? 0;
        $message = $error['message'] ?? 'Server Error';

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
