<?php

namespace Attogram\SharedMedia\Gallery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.4';

    protected function getRoutes()
    {
        return [
            'admin'      => [''],
            'debug'      => ['debug'],
        ];
    }
}
