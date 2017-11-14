<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Router
{
    const VERSION = '0.0.5';

    protected function getRoutes()
    {
        return [
            'home'       => [''],
            'about'      => ['about'],
            'categories' => ['category'],
            'category'   => ['category', '*'],
            'medias'     => ['media'],
            'media'      => ['media', '*'],
        ];
    }

    protected function controlMedia()
    {
        if (!$this->isNumber($this->uri[1])) {
            $this->error404('404 Media Not Found');
            return false;
        }
        return true;
    }

    protected function controlCategory()
    {
        if (!$this->isNumber($this->uri[1])) {
            $this->error404('404 Media Not Found');
            return false;
        }
        return true;
    }
    private function isNumber($var)
    {
        if (preg_match('/^[0-9]*$/', $var)) {
            return true;
        }
        return false;
    }
}
