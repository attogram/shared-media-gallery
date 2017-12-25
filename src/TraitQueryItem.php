<?php

namespace Attogram\SharedMedia\Gallery;

use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

trait TraitQueryItem
{
    private $defaultItemsPerPage = 20;

     /**
     * @param object $orm
     * @param string $dataName
     * @param int|null $itemsPerPage
     * @return void
     */
    private function setItems($orm, $dataName, $itemsPerPage = 0)
    {
        if (!$itemsPerPage) {
            $itemsPerPage = $this->defaultItemsPerPage;
        }
        $page = 1;
        try {
            $items = $orm->orderByTitle()->paginate($page, $itemsPerPage);
        } catch (Exception $error) {
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
}
