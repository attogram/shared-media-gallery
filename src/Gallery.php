<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Tools;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

class Gallery extends Router
{
    const VERSION = '0.0.17';

    public function __construct(int $level = 0)
    {
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
        return $this->setItems(MediaQuery::create(), 'medias');
    }

    protected function controlCategories()
    {
        $orm = CategoryQuery::create();
        $query = Tools::getGet('q');
        if (!empty($query)) {
            $orm->filterByTitle("%$query%", Criteria::LIKE);
            $this->data['query'] = $query;
        }
        return $this->setItems($orm, 'categories');
    }

    protected function controlPages()
    {
        return $this->setItems(PageQuery::create(), 'pages');
    }

    protected function controlMedia()
    {
        return $this->setItem(MediaQuery::create(), 'media');
    }

    protected function controlCategory()
    {
        return $this->setItem(CategoryQuery::create(), 'category');
    }

    protected function controlPage()
    {
        return $this->setItem(PageQuery::create(), 'page');
    }

    /**
     * @param object $orm
     * @param string $dataName
     * @return bool
     */
    private function setItems($orm, $dataName)
    {
        $page = 1;
        $maxPerPage = 100;
        $items = $orm->orderByTitle()->paginate($page, $maxPerPage);
        if (!$items) {
            return true;
        }
        foreach ($items as $item) {
            $this->data[$dataName][] = $item->toArray(TableMap::TYPE_FIELDNAME);
        }
        return true;
    }

    /**
     * @param object $orm
     * @param string $dataName
     * @return bool
     */
    private function setItem($orm, $dataName)
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('400 ' . ucfirst($dataName) . ' Not Found');
            return false;
        }
        $item = $orm->filterByPageid($this->uri[1])->findOne();
        if (!$item) {
            $this->error404('404 ' . ucfirst($dataName) . ' Not Found');
            return false;
        }
        $this->data[$dataName] = $item;

        return true;
    }
}
