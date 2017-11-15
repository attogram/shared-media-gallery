<?php

namespace Attogram\SharedMedia\Gallery;

class Tools
{
    const VERSION = '0.0.2';

    /**
     * @param mixed $var
     * @return bool
     */
    public static function isNumber($var)
    {
        if (preg_match('/^[0-9]*$/', $var)) {
            return true;
        }
        return false;
    }

    /**
     * get the value of a global _SERVER variable
     * @param string $name
     * @return mixed
     */
    public static function getServer(string $name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }

    /**
     * get the value of a global _GET variable
     *
     * @param string $name
     * @return string
     */
    public static function getGet(string $name)
    {
        if (self::hasGet($name)) {
            return trim(urldecode($_GET[$name]));
        }
        return '';
    }

    /**
     * Check if a global _GET variable is set
     *
     * @param string $name
     * @return mixed
     */
    public static function hasGet(string $name)
    {
        if (!empty($_GET[$name])) {
            return true;
        }
        return false;
    }
}
