<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Base
{
    const VERSION = '0.0.1';

    protected $twig;

    protected function route()
    {
        $this->homePage();
    }

    private function homePage()
    {
        $data = [
            'title' => 'Shared Media Gallery',
            'version' => self::VERSION,
        ];
        $this->twig->display('home.twig', $data);
    }
}
