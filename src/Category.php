<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class Category
 * @package Attogram\SharedMedia\Gallery
 */
class Category extends PublicItem
{
    use TraitQueryPublic;

    /**
     * @return \Attogram\SharedMedia\Orm\CategoryQuery
     */
    public function getQuery()
    {
        return $this->getCategoryQuery();
    }

    /**
     * @return string
     */
    public function getNameAll()
    {
        return 'categories';
    }

    /**
     * @return string
     */
    public function getNameOne()
    {
        return 'category';
    }
}
