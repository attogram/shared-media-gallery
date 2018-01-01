<?php

namespace Attogram\SharedMedia\Gallery;

class Page extends PublicItem
{
    use TraitQueryPublic;

    public function getQuery()
    {
        return $this->getPageQuery();
    }

    public function getNameAll()
    {
        return 'pages';
    }

    public function getNameOne()
    {
        return 'page';
    }
}
