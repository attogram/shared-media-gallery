<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Attogram\SharedMedia\Orm\SourceQuery;
use Throwable;

trait TraitCounts
{
    /**
     * @return void
     */
    private function setCounts()
    {
        $this->data['category_count'] = $this->getCategoryCount();
        $this->data['media_count'] = $this->getMediaCount();
        $this->data['page_count'] = $this->getPageCount();
        $this->data['source_count'] = $this->getSourceCount();
    }

    /**
     * @return int
     */
    private function getCategoryCount()
    {
        return $this->getCount(new CategoryQuery());
    }

    /**
     * @return int
     */
    private function getMediaCount()
    {
        return $this->getCount(new MediaQuery());
    }

    /**
     * @return int
     */
    private function getPageCount()
    {
        return $this->getCount(new PageQuery());
    }

    /**
     * @return int
     */
    private function getSourceCount()
    {
        return $this->getCount(new SourceQuery());
    }

    /**
     * @param ojbect $orm
     */
    private function getCount($orm)
    {
        try {
            return $orm->count();
        } catch (Throwable $error) {
            return 0;
        }
    }
}
