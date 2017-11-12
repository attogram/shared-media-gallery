<?php

namespace Attogram\SharedMedia\Gallery;

class Gallery extends Base
{
    const VERSION = '0.0.2';

    protected function route()
    {
        $uri = explode('/', $this->uriRelative);
        switch ($uri[1]) {
            case '':
                $this->twig->display('home.twig', $this->getDefaultData());
                break;
            case 'about':
                $this->twig->display('about.twig', $this->getDefaultData());
                break;
            case 'admin':
                $this->twig->display('admin.twig', $this->getDefaultData());
                break;
            default:
                header('HTTP/1.0 404 Not Found');
                $this->twig->display('error.twig', $this->getDefaultData());
                break;
        }
    }
}
