<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/4.x/README.md
 * @contact  huangdijia@gmail.com
 */
if (! function_exists('retry')) {
    /**
     * Retry.
     * @template T
     *
     * @param callable(int):T $callback
     * @param (callable(Throwable):mixed)|null $when
     * @return T
     * @throws Throwable
     */
    function retry(int $times, callable $callback, int $sleep = 0, ?callable $when = null)
    {
        $attempts = 0;

        beginning:
        $attempts++;
        --$times;

        try {
            return $callback($attempts);
        } catch (Throwable $e) {
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
}

if (! function_exists('throw_if')) {
    /**
     * @template TValue
     *
     * @param TValue $condition
     * @param string|\Throwable $exception
     * @return TValue
     * @throws InvalidArgumentException
     * @throws Exception
     */
    function throw_if($condition, $exception, ...$parameters)
    {
        if ($condition) {
            throw is_string($exception) ? new $exception(...$parameters) : $exception;
        }

        return $condition;
    }
}

if (! function_exists('tap')) {
    /**
     * @template TValue
     *
     * @param TValue $value
     * @param (callable(TValue):mixed)|null $callback
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
}

if (! function_exists('with')) {
    /**
     * @template TValue
     * @template TReturn
     *
     * @param TValue $value
     * @param callable(TValue):TReturn|null $callback
     * @return ($callback is null ? TValue : TReturn)
     */
    function with($value, ?callable $callback = null)
    {
        return is_null($callback) ? $value : $callback($value);
    }
}

if (! function_exists('str_start')) {
    /**
     * Begin a string with a single instance of a given value.
     */
    function str_start(string $value, string $prefix): string
    {
        $quoted = preg_quote($prefix, '/');

        return $prefix . preg_replace('/^(?:' . $quoted . ')+/u', '', $value);
    }
}

if (! function_exists('str_starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|string[] $needles
     * @return bool
     */
    function str_starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('str_snake')) {
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
}

if (! function_exists('str_lower')) {
    /**
     * @return string
     */
    function str_lower(string $value)
    {
        return mb_strtolower($value, 'UTF-8');
    }
}

if (! function_exists('str_studly')) {
    /**
     * @return string
     */
    function str_studly(string $value, string $gap = '')
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', $gap, $value);
    }
}

if (! function_exists('str_replace_first')) {
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
}

if (! function_exists('str_replace_array')) {
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
}

if (! function_exists('value')) {
    /**
     * @template TValue
     * @template TReturn
     *
     * @param TValue|(Closure():TReturn) $value
     * @param TValue $value
     * @return ($value is Closure ? TReturn : TValue)
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param int|string|null $key
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
}

if (! function_exists('array_has')) {
    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param array|string|null $keys
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
}

if (! function_exists('array_exists')) {
    /**
     * @param mixed $array
     * @param int|string $key
     * @return bool
     */
    function array_exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

if (! function_exists('array_accessible')) {
    /**
     * @param mixed $value
     * @return bool
     */
    function array_accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}
