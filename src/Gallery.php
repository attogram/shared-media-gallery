<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Tools;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Map\TableMap;

class Gallery extends Router
{
    const VERSION = '0.0.11';

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
    protected function controlHome()
    {
        $this->data['media'] = MediaQuery::create()
            ->setOffset(rand(1, $this->data['category_count'] - 1))
            ->findOne();
        return true;
    }

    protected function controlMedias()
    {
        $page = 1;
        $maxPerPage = 100;
        $medias = MediaQuery::create()->paginate($page, $maxPerPage);
        if (!$medias) {
            return true;
        }
        foreach ($medias as $media) {
            $this->data['medias'][] = $media->toArray(TableMap::TYPE_FIELDNAME);
        }

        return true;
    }

    protected function controlMedia()
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('400 Media Not Found');
            return false;
        }
        $media = MediaQuery::create()->filterByPageid($this->uri[1])->findOne();
        if (!$media) {
            $this->error404('404 Media Not Found');
            return false;
        }
        $this->data['media'] = $media;

        return true;
    }

    protected function controlCategories()
    {
        $page = 1;
        $maxPerPage = 100;
        $categories = CategoryQuery::create()->paginate($page, $maxPerPage);
        if (!$categories) {
            return true;
        }
        foreach ($categories as $category) {
            $this->data['categories'][] = $category->toArray(TableMap::TYPE_FIELDNAME);
        }

        return true;
    }

    protected function controlCategory()
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('400 Category Not Found');
            return false;
        }
        $category = CategoryQuery::create()->filterByPageid($this->uri[1])->findOne();
        if (!$category) {
            $this->error404('404 Category Not Found');
            return false;
        }
        $this->data['category'] = $category;

        return true;
    }
}
