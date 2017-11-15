<?php

namespace Attogram\SharedMedia\Gallery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.6';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery ADMIN';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

    protected function getRoutes()
    {
        return [
            'admin/home'     => [''],
            'admin/media'    => ['media'],
            'admin/category' => ['category'],
            'admin/debug'    => ['debug'],
        ];
    }

    protected function controlAdminMedia()
    {
        return true;
    }

    protected function controlAdminCategory()
    {
        if (Tools::hasGet('q')) {
            $this->data['query'] = Tools::getGet('q');
        }
        return true;
    }
}
