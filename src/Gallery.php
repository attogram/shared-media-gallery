<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Tools;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

class Gallery extends Base
{
    const VERSION = '0.0.21';

    /**
     * @param int|null $level
     */
    public function __construct(int $level = 0)
    {
        parent::__construct($level);
    }

    /**
     * @return array
     */
    protected function getRoutes()
    {
        return [
            'home'       => [''],
            'about'      => ['about'],
            'categories' => ['category'],
            'category'   => ['category', '*'],
            'medias'     => ['media'],
            'media'      => ['media', '*'],
            'pages'      => ['page'],
            'page'       => ['page', '*'],
        ];
    }

    /**
     * @return bool
     */
    protected function controlHome()
    {
        $this->data['media'] = MediaQuery::create()
            ->setOffset(rand(1, $this->data['category_count'] - 1))
            ->findOne();
        return true;
    }

    protected function controlMedias()
    {
        $orm = $this->setupSearch(MediaQuery::create());
        return $this->setItems($orm, 'medias');
    }

    protected function controlCategories()
    {
        $orm = $this->setupSearch(CategoryQuery::create());
        return $this->setItems($orm, 'categories', 100);
    }

    protected function controlPages()
    {
        $orm = $this->setupSearch(PageQuery::create());
        return $this->setItems($orm, 'pages');
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
     * Setup search query
     *
     * @param object $orm
     * @return object orm
     */
    private function setupSearch($orm)
    {
        $query = Tools::getGet('q');
        if (!empty($query)) {
            $orm->filterByTitle("%$query%", Criteria::LIKE);
            $this->data['query'] = $query;
        }
        return $orm;
    }

    /**
     * @param object $orm
     * @param string $dataName
     * @param int|null $itemsPerPage
     * @return bool
     */
    private function setItems($orm, $dataName, $itemsPerPage = self::ITEMS_PER_PAGE)
    {
        $page = 1;
        $items = $orm->orderByTitle()->paginate($page, $itemsPerPage);
        if (!$items) {
            return true;
        }
        foreach ($items as $item) {
            $itemArray = $item->toArray(TableMap::TYPE_FIELDNAME);
            $itemArray['shortTitle'] = Tools::stripPrefix($itemArray['title']);
            $this->data[$dataName][] = $itemArray;
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
