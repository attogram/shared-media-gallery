<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Tools;

class Gallery extends Router
{
    const VERSION = '0.0.9';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

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
