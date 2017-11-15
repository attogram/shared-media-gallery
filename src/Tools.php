<?php

namespace Attogram\SharedMedia\Gallery;

class Tools
{
    const VERSION = '0.0.1';

    public static function isNumber($var)
    {
        if (preg_match('/^[0-9]*$/', $var)) {
            return true;
        }
        return false;
    }

    public static function getServer($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }
}
