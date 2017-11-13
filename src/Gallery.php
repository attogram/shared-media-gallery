<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Base
{
    const VERSION = '0.0.3';

    protected function getRoutes()
    {
        return [
            'home' =>  [''],
            'about' => ['about'],
            'admin' => ['admin'],
            'debug' => ['admin', '*'],
        ];
    }

    protected function getViewData()
    {
        return [
            'title'       => 'Shared Media Gallery',
            'version'     => self::VERSION,
            'uriBase'     => $this->uriBase,
            'uriRelative' => $this->uriRelative,
            'uri'         => $this->uri,
            'routes'      => $this->getRoutes(),
        ];
    }

}
