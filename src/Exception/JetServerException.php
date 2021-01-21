<?php

class JetServerException extends JetException
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
        $code = 0;
        $message = 'Server Error';

        if (JetUtil::arrayHas($error, 'data.code')) {
            $code = JetUtil::arrayGet($error, 'data.code');
        } elseif (JetUtil::arrayHas($error, 'code')) {
            $code = JetUtil::arrayGet($error, 'code');
        }

        if (JetUtil::arrayHas($error, 'data.message')) {
            $message = JetUtil::arrayGet($error, 'data.message');
        } elseif (JetUtil::arrayHas($error, 'message')) {
            $message = JetUtil::arrayGet($error, 'message');
        }

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
