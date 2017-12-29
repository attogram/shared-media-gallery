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
        $this->data['category_count'] = $this->getCount(new CategoryQuery());
        $this->data['media_count'] = $this->getCount(new MediaQuery());
        $this->data['page_count'] = $this->getCount(new PageQuery());
        $this->data['source_count'] = $this->getCount(new SourceQuery());
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
