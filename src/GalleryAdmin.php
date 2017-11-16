<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.7';

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
        if (!Tools::hasGet('q')) {
            return true;
        }
        $this->data['query'] = Tools::getGet('q');
        $mediaQuery = new MediaQuery();
        $results = $mediaQuery->search($this->data['query']);
        $this->data['results'] = $mediaQuery->format($results);

        return true;
    }

    protected function controlAdminCategory()
    {
        if (!Tools::hasGet('q')) {
            return true;
        }
        $this->data['query'] = Tools::getGet('q');
        $categoryQuery = new CategoryQuery();
        $results = $categoryQuery->search($this->data['query']);
        $this->data['results'] = $categoryQuery->format($results);

        return true;
    }
}
