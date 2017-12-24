<?php

namespace Attogram\SharedMedia\Gallery;

class Tools
{
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
     * get the value of a global _GET variable
     *
     * @param string $name
     * @return mixed
     */
    public static function getGet(string $name)
    {
        if (!empty($_GET[$name])) {
            return trim(urldecode($_GET[$name]));
        }
    }

    /**
     * get the value of a global _POST variable
     *
     * @param string $name
     * @return mixed
     */
    public static function getPost(string $name)
    {
        if (!empty($_POST[$name])) {
            return $_POST[$name];
        }
    }

    /**
     * strip Category: or File: prefix
     *
     * @param string $string
     * @return string
     */
    public static function stripPrefix($string)
    {
        if (!$string || !is_string($string)) {
            return '';
        }
        return preg_replace(
            array('/^File:/', '/^Category:/'),
            '',
            $string
        );
    }
}
