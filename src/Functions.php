<?php

declare(strict_types=1);
/**
 * This file is part of jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendsofhyperf/jet/blob/main/LICENSE
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
 * @param string $delimiter
 * @return string
 */
function str_snake(string $value, $delimiter = '_')
{
    if (! ctype_lower($value)) {
        $value = preg_replace('/\s+/u', '', ucwords($value));
        $value = str_lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
    }

    return $value;
}

/**
 * @return string
 */
function str_lower(string $value)
{
    return mb_strtolower($value, 'UTF-8');
}

/**
 * @return string
 */
function str_studly(string $value, string $gap = '')
{
    $value = ucwords(str_replace(['-', '_'], ' ', $value));

    return str_replace(' ', $gap, $value);
}

/**
 * @return string
 */
function str_replace_first(string $search, string $replace, string $subject)
{
    if ($search == '') {
        return $subject;
    }

    $position = strpos($subject, $search);

    if ($position !== false) {
        return substr_replace($subject, $replace, $position, strlen($search));
    }

    return $subject;
}

/**
 * @return string
 */
function str_replace_array(string $search, array $replace, string $subject)
{
    foreach ($replace as $value) {
        $subject = str_replace_first($search, (string) $value, $subject);
    }

    return $subject;
}

/**
 * @param mixed $value
 * @return mixed
 */
function value($value)
{
    return $value instanceof \Closure ? $value() : $value;
}

/**
 * Get an item from an array using "dot" notation.
 *
 * @param array|\ArrayAccess $array
 * @param null|int|string $key
 * @param mixed $default
 */
function array_get($array, $key = null, $default = null)
{
    if (is_null($key)) {
        return $array;
    }

    if (isset($array[$key])) {
        return $array[$key];
    }

    if (! is_string($key) || strpos($key, '.') === false) {
        return $array[$key] ?? value($default);
    }

    foreach (explode('.', $key) as $segment) {
        if (array_accessible($array) && array_exists($array, $segment)) {
            $array = $array[$segment];
        } else {
            return value($default);
        }
    }

    return $array;
}

/**
 * Check if an item or items exist in an array using "dot" notation.
 *
 * @param array|\ArrayAccess $array
 * @param null|array|string $keys
 */
function array_has($array, $keys)
{
    if (is_null($keys)) {
        return false;
    }

    $keys = (array) $keys;

    if (! $array || $keys === []) {
        return false;
    }

    foreach ($keys as $key) {
        $subKeyArray = $array;

        if (array_exists($array, $key)) {
            continue;
        }

        foreach (explode('.', $key) as $segment) {
            if (array_accessible($subKeyArray) && array_exists($subKeyArray, $segment)) {
                $subKeyArray = $subKeyArray[$segment];
            } else {
                return false;
            }
        }
    }

    return true;
}

/**
 * @param mixed $array
 * @param int|string $key
 * @return bool
 */
function array_exists($array, $key)
{
    if ($array instanceof \ArrayAccess) {
        return $array->offsetExists($key);
    }

    return array_key_exists($key, $array);
}

/**
 * @param mixed $value
 * @return bool
 */
function array_accessible($value)
{
    return is_array($value) || $value instanceof \ArrayAccess;
}
