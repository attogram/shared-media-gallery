<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Throwable;

trait TraitQueryPublic
{
    private $defaultItemsPerPage = 50;

    private function setLimit()
    {
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
    }

    private function getCategoryQuery()
    {
        return $this->joinWithSource(CategoryQuery::create());
    }

    private function getMediaQuery()
    {
        return $this->joinWithSource(MediaQuery::create());
    }

    private function getPageQuery()
    {
        return $this->joinWithSource(PageQuery::create());
    }

    private function joinWithSource($ormQuery)
    {
        return $ormQuery
            ->joinWith('Source')
            ->withColumn('source.id')
            ->withColumn('source.title')
            ->withColumn('source.host')
            ->withColumn('source.endpoint');
    }

    /**
     * @param object   $orm
     * @param string   $name
     * @param int      $limit
     */
    private function displayItems($orm, $name, $limit = 0)
    {
        $orm = $this->setupSearch($orm);
        $this->setItems($orm, $name, $limit);
        $this->displayView($name);
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
            $itemsPerPage = $this->defaultItemsPerPage;
        }
        $page = 1;
        try {
            $items = $orm
                ->orderByTitle()
                ->paginate($page, $itemsPerPage);
            if (!$items) {
                return;
            }
            foreach ($items as $item) {
                $itemArray = $item->toArray(TableMap::TYPE_FIELDNAME);
                $itemArray['shortTitle'] = $this->stripPrefix($itemArray['title']);
                $this->data[$dataName][] = $itemArray;
            }
        } catch (Throwable $error) {
            $this->fatalError(
                'setItems: ' . $error->getMessage()
                . '<pre>Trace:  ' . $error->getTraceAsString()
            );
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
            $this->error404('404 ' . ucfirst($dataName) . ' Not Found');
        }
        try {
            $item = $orm->filterByPageid($itemId)->findOne();
        } catch (Throwable $error) {
            $item = false;
        }
        if (!$item) {
            $this->error404('404 ' . ucfirst($dataName) . ' Not Found');
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
     * @param object   $orm
     * @param string   $name
     */
    private function displayItem($orm, $name)
    {
        if (!$this->setItem($orm, $name)) {
            return;
        }
        $this->displayView($name);
    }
}
