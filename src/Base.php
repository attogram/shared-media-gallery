<?php

namespace Attogram\SharedMedia\Gallery;

class Base
{
    const VERSION = '0.0.1';

    protected $twig;

    public function __construct()
    {
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem('../views/'),
            [
                'cache' => '../cache/',
                'auto_reload' => true,
            ]
        );
        $this->route();
    }
    
    protected function route()
    {
    }
}
