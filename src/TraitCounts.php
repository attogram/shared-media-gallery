<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Attogram\SharedMedia\Orm\SourceQuery;
use Exception;

trait TraitCounts
{
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
        } catch (Exception $error) {
            return 0;
        }
    }
}
