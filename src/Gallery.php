<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\Tools;

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
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('404 Media Not Found');
            return false;
        }
        return true;
    }

    protected function controlCategory()
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('404 Category Not Found');
            return false;
        }
        return true;
    }

}
