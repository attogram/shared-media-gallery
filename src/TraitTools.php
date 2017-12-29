<?php

namespace Attogram\SharedMedia\Gallery;

trait TraitTools
{
    /**
     * @param mixed $var
     * @return bool
     */
    private function isNumber($var)
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
    private function getGet(string $name)
    {
        if (!empty($_GET[$name])) {
            return $_GET[$name];
        }
    }

    /**
     * get the value of a global _POST variable
     *
     * @param string $name
     * @return mixed
     */
    private function getPost(string $name)
    {
        if (!empty($_POST[$name])) {
            return $_POST[$name];
        }
    }

    /**
     * @return bool
     */
    private function isPost()
    {
        if (!empty($_POST)) {
            return true;
        }
        return false;
    }

    /**
     * get the value of a global _SERVER variable
     *
     * @param string $name
     * @return mixed
     */
    private function getServer(string $name)
    {
        if (!empty($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }

    /**
     * strip Category: or File: prefix
     *
     * @param string $string
     * @return string
     */
    private function stripPrefix($string)
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
	
    /**
     * @param string $url
     */
    private function redirect301($url)
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit;
    }
	
    /**
     * @param string $url
     */
    private function redirect302($url)
    {
        header('HTTP/1.1 302 Found');
        header('Location: ' . $url);
        exit;
    }
}
