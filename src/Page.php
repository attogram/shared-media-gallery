<?php

namespace Attogram\SharedMedia\Gallery;

class Page
{
    use TraitPublicItem;

    private $nameAll = 'pages';
    private $nameOne = 'page';

    private function getQuery()
    {
        return $this->getPageQuery();
    }
}
