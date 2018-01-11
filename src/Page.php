<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class Page
 * @package Attogram\SharedMedia\Gallery
 */
class Page extends PublicItem
{
    use TraitQueryPublic;

    /**
     * @return \Attogram\SharedMedia\Orm\PageQuery
     */
    public function getQuery()
    {
        return $this->getPageQuery();
    }

    /**
     * @return string
     */
    public function getNameAll()
    {
        return 'pages';
    }

    /**
     * @return string
     */
    public function getNameOne()
    {
        return 'page';
    }
}
