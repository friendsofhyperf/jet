<?php

namespace FriendsOfHyperf\Jet\Support;

class Str
{
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
}