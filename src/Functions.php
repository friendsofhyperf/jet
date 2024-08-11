<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace FriendsOfHyperf\Jet;

/**
 * @template TReturn
 *
 * @param callable(int):TReturn $callback
 * @return TReturn
 * @throws \Throwable
 */
function retry(int $times, callable $callback, int $sleep = 0, ?callable $when = null)
{
    $attempts = 0;

    beginning:
    $attempts++;
    --$times;

    try {
        return $callback($attempts);
    } catch (\Throwable $e) {
        if ($times < 1 || ($when && ! $when($e))) {
            throw $e;
        }

        if ($sleep) {
            usleep($sleep * 1000);
        }

        goto beginning;
    }

    return null;
}

/**
 * @template TValue
 *
 * @param TValue $condition
 * @param class-string<\Throwable>|\Throwable $exception
 * @return TValue
 * @throws \Throwable
 */
function throw_if($condition, $exception, ...$parameters)
{
    if ($condition) {
        throw is_string($exception) ? new $exception(...$parameters) : $exception;
    }

    return $condition;
}

/**
 * @template TValue
 *
 * @param TValue $value
 * @return TValue
 */
function tap($value, ?callable $callback = null)
{
    if (is_null($callback)) {
        return new class($value) {
            public $target;

            public function __construct($target)
            {
                $this->target = $target;
            }

            public function __call($method, $parameters)
            {
                $this->target->{$method}(...$parameters);

                return $this->target;
            }
        };
    }

    $callback($value);

    return $value;
}

/**
 * @template TValue
 * @template TReturn
 *
 * @param TValue $value
 * @param null|(callable(TValue):TReturn) $callback
 * @return ($callback is null ? TValue : TReturn)
 */
function with($value, ?callable $callback = null) // @phpstan-ignore-line
{
    return is_null($callback) ? $value : $callback($value);
}

/**
 * @param mixed $value
 * @return mixed
 */
function value($value)
{
    return $value instanceof \Closure ? $value() : $value;
}
