<?php

namespace FriendsOfHyperf\Jet\Support;

class Util
{
    /**
     * Retry
     * @template T
     * @param int $times
     * @param callable(int):T $callback
     * @param int $sleep
     * @param null|(callable(\Exception)) $when
     * @return T
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
     * @template TValue
     * @param TValue $value
     * @param \Exception $exception
     * @return TValue
     * @throws \InvalidArgumentException
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
     * @template TValue
     * @param TValue $value
     * @param null|(callable(TValue)) $callback
     * @return TValue
     */
    public static function tap($value, $callback = null)
    {
        if (!is_null($callback) && is_callable($callback)) {
            $callback($value);
        }

        return $value;
    }

    /**
     * @template T
     * @template TValue
     * @param mixed $value
     * @param null|(callable(TValue):T) $callback
     * @return ($callback is null ? TValue : T)
     */
    public static function with($value, $callback)
    {
        if (!is_null($callback) && is_callable($callback)) {
            return $callback($value);
        }

        return $value;
    }

    /**
     * @template T
     * @template TValue
     * @param TValue|(\Closure(): T) $value
     * @return ($value is \Closure ? T : TValue)
     */
    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
