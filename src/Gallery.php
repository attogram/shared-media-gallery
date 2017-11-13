<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Base
{
    const VERSION = '0.0.4';

    protected function getRoutes()
    {
        return [
            'home' =>  [''],
            'about' => ['about'],
            'about1' => ['about', '1'],
            'about2' => ['about', '2'],
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
