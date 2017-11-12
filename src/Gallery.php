<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery
{
    const VERSION = '0.0.1';

    private $twig;

    public function __construct()
    {
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem('../views/'),
            [
                //'cache' => '../cache/',
            ]
        );
        $this->homePage();
    }

    private function homePage()
    {
        $this->twig->display('home.twig');
    }
}
