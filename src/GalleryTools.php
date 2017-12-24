<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Exception\RuntimeException;

class GalleryTools
{
    /**
     * @return int
     */
    public function getCategoryCount()
    {
        return $this->getCount(new CategoryQuery());
    }

    /**
     * @return int
     */
    public function getMediaCount()
    {
        return $this->getCount(new MediaQuery());
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return $this->getCount(new PageQuery());
    }

    /**
     * @return int
     */
    public function getSourceCount()
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
        } catch (RuntimeException $error) {
            print '<pre>ERROR: getCount: orm:' . get_class($orm) . ': ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }
}
