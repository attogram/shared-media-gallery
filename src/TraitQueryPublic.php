<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Throwable;

/**
 * Trait TraitQueryPublic
 * @package Attogram\SharedMedia\Gallery
 */
trait TraitQueryPublic
{
	private $pageid;
	private $sourceId;
    private $defaultItemsPerPage = 50;

	private function setSourceIdAndPageid()
	{
		if (empty($this->data['vars'][0]) || !$this->isNumber($this->data['vars'][0])) {
			$this->fatalError('Invalid Source ID');
		}
		if (empty($this->data['vars'][1]) || !$this->isNumber($this->data['vars'][1])) {
			$this->fatalError('Invalid Pageid');
		}
        $this->sourceId = $this->data['vars'][0];
        $this->pageid = $this->data['vars'][1];
	}

    private function setLimit()
    {
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
    }

    /**
     * @return object
     */
    private function getCategoryQuery()
    {
        return $this->joinWithSource(CategoryQuery::create());
    }

    /**
     * @return object
     */
    private function getMediaQuery()
    {
        return $this->joinWithSource(MediaQuery::create());
    }

    /**
     * @return object
     */
    private function getPageQuery()
    {
        return $this->joinWithSource(PageQuery::create());
    }

    /**
     * @param object $ormQuery
     * @return object
     */
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
        $this->setItem($orm, $name);
        $this->displayView($name);
    }

    /**
     * @param object $orm
     * @param string $dataName
     */
    private function setItem($orm, $dataName)
    {
        $this->setSourceIdAndPageid();
        try {
            $item = $orm
                ->filterBySourceId($this->sourceId)
                ->filterByPageid($this->pageid)
                ->findOne();
            if (!$item) {
                $this->error404(ucfirst($dataName) . ' Not Found');
            }
            $this->data[$dataName] = $item->toArray(TableMap::TYPE_FIELDNAME);
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
    }
}
