<?php

class JetUtil
{
    /**
     * Retry
     * @param int $times
     * @param Closure $callback
     * @param int $sleep
     * @param null|Closure $when
     * @return mixed
     */
    public static function retry($times, $callback, $sleep = 0, $when = null)
    {
        $attempts = 0;

        beginning:
        $attempts++;
        $times--;

        try {
            return $callback($attempts);
        } catch (Exception $e) {
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
     * @param Exception $exception
     * @return mixed
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function throwIf($value, $exception)
    {
        if (!($exception instanceof Exception)) {
            throw new InvalidArgumentException('$exception is not instanceof Exception');
        }

        if ($value) {
            throw $exception;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param Closure|null $callback
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
     * @param Closure|null $callback
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
     * @param string $value
     * @param string $delimiter
     * @return mixed
     */
    public static function snake($value, $delimiter = '_')
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * @param string $value
     * @param string $gap
     * @return string
     */
    public static function studly($value, $gap = '')
    {
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));

        return str_replace(' ', $gap, $value);
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
    public static function replaceFirst($search, $replace, $subject)
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
     * @param array $search
     * @param array $replace
     * @param string $subject
     * @return string
     */
    public static function replaceArray($search, $replace, $subject)
    {
        foreach ($replace as $value) {
            $subject = self::replaceFirst($search, $value, $subject);
        }

        return $subject;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param null|int|string $key
     * @param mixed $default
     */
    public static function arrayGet($array, $key = null, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        if (!is_string($key) || strpos($key, '.') === false) {
            return isset($array[$key]) ? $array[$key] : self::value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return self::value($default);
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
    public static function arrayHas($array, $keys)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array) $keys;

        if (!$array || $keys === array()) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     *
     * @param mixed $array
     * @param int|string $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}
