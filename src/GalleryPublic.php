<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

class GalleryPublic
{
    use TraitTools;
    use TraitView;

    const ITEMS_PER_PAGE = 10;

    private $data = [];

    /**
     * @param array $data
     */
    public function home($data)
    {
        try {
            $data['media'] = MediaQuery::create()
                ->setOffset(rand(1, $data['category_count'] - 1))
                ->findOne();
        } catch (Exception $error) {
            $data['media'] = ['title' => 'Error', 'pageid' => 0];
        }
        $this->displayView('home', $data);
    }

    public function about($data)
    {
        $this->displayView('about', $data);
    }

    public function medias($data)
    {
        $this->displayItems($data, MediaQuery::create(), 'medias');
    }

    public function categories($data)
    {
        $this->displayItems($data, CategoryQuery::create(), 'categories', 100);
    }

    public function pages($data)
    {
        $this->displayItems($data, PageQuery::create(), 'pages');
    }

    /**
     * @param array    $data
     * @param object   $orm
     * @param string   $name
     * @param int      $limit
     */
    private function displayItems($data, $orm, $name, $limit = 0)
    {
        $this->data = $data;
        $orm = $this->setupSearch($orm);
        $this->setItems($orm, $name, $limit);
        $this->displayView($name, $this->data);
    }

    /**
     * Setup search query
     *
     * @param object $orm
     * @return object orm
     */
    private function setupSearch($orm)
    {
        $query = $this->getGet('q');
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
     * @return void
     */
    private function setItems($orm, $dataName, $itemsPerPage = 0)
    {
        if (!$itemsPerPage) {
            $itemsPerPage = self::ITEMS_PER_PAGE;
        }
        $page = 1;
        try {
            $items = $orm->orderByTitle()->paginate($page, $itemsPerPage);
        } catch(Exception $error) {
            return;
        }
        if (!$items) {
            return;
        }
        foreach ($items as $item) {
            $itemArray = $item->toArray(TableMap::TYPE_FIELDNAME);
            $itemArray['shortTitle'] = $this->stripPrefix($itemArray['title']);
            $this->data[$dataName][] = $itemArray;
        }
    }

    public function media($data)
    {
        $this->displayItem($data, MediaQuery::create(), 'media');
    }

    public function category($data)
    {
        $this->displayItem($data, CategoryQuery::create(), 'category');
    }

    public function page($data)
    {
        $this->displayItem($data, PageQuery::create(), 'page');
    }

    /**
     * @param array    $data
     * @param object   $orm
     * @param string   $name
     */
    private function displayItem($data, $orm, $name)
    {
        $this->data = $data;
        if (!$this->setItem($orm, $name)) {
            return;
        }
        $this->displayView($name, $this->data);
    }

    /**
     * @param object $orm
     * @param string $dataName
     * @return bool
     */
    private function setItem($orm, $dataName)
    {
        $itemId = $this->data['vars'][0];
        if (!$this->isNumber($itemId)) {
            $this->error404('400 ' . ucfirst($dataName) . ' Not Found');
            return false;
        }
        try {
            $item = $orm->filterByPageid($itemId)->findOne();
        } catch (Exception $error) {
            $item = false;
        }
        if (!$item) {
            $this->error404('404 ' . ucfirst($dataName) . ' Not Found');
            return false;
        }
        $this->data[$dataName] = $item;
        return true;
    }
}
