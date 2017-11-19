<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.11';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery Admin';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

    protected function getRoutes()
    {
        return [
            'admin/home'       => [''],
            'admin/media'      => ['media'],
            'admin/media.save' => ['media', 'save'],
            'admin/category'   => ['category'],
            'admin/debug'      => ['debug'],
        ];
    }

    protected function controlAdminMedia()
    {
        if (!Tools::hasGet('q')) {
            return true;
        }
        $this->data['query'] = Tools::getGet('q');
        $mediaQuery = new MediaQuery();
        $this->data['results'] = $mediaQuery->search($this->data['query']);
        //foreach ($this->data['results'] as $res) {
        //    $res->save();
        //}

        return true;
    }

    protected function controlAdminMediasave()
    {
        $this->data['post'] = $_POST;
        return true;
    }

    protected function controlAdminCategory()
    {
        if (!Tools::hasGet('q')) {
            return true;
        }
        $this->data['query'] = Tools::getGet('q');
        $categoryQuery = new CategoryQuery();
        $this->data['results'] = $categoryQuery->search($this->data['query']);
        //foreach ($this->data['results'] as $res) {
        //    $res->save();
        //}

        return true;
    }
}
