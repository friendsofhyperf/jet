<?php

namespace FriendsOfHyperf\Jet\Support;

class Util
{
    /**
     * Retry
     * @param int $times
     * @param \Closure $callback
     * @param int $sleep
     * @param null|\Closure $when
     * @return mixed
     */
    public static function retry($times, $callback, $sleep = 0, $when = null)
    {
        $times = (int) $times;
        $attempts = 0;

        beginning:
        $attempts++;
        $times--;

        try {
            return $callback($attempts);
        } catch (\Exception $e) {
            if ($times < 1 || ($when && !$when($e))) {
                throw $e;
            }

            if ($sleep) {
                usleep($sleep * 1000);
            }

            goto beginning;
        }
    }

    /**
     * @param mixed $value
     * @param \Exception $exception
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function throwIf($value, $exception)
    {
        if (!($exception instanceof \Exception)) {
            throw new \InvalidArgumentException('$exception is not instanceof Exception');
        }

        if ($value) {
            throw $exception;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param \Closure|null $callback
     * @return mixed
     */
    public static function tap($value, $callback = null)
    {
        if (!is_null($callback) && is_callable($callback)) {
            $callback($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param \Closure|null $callback
     * @return mixed
     */
    public static function with($value, $callback)
    {
        if (!is_null($callback) && is_callable($callback)) {
            return $callback($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
