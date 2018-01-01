<?php

namespace Attogram\SharedMedia\Gallery;

class Category
{
    use TraitPublicItem;

    private $nameAll = 'categories';
    private $nameOne = 'category';

    private function getQuery()
    {
        return $this->getCategoryQuery();
    }
}
