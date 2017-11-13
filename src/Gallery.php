<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Router
{
    const VERSION = '0.0.4';

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
}
