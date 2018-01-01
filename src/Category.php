<?php

namespace Attogram\SharedMedia\Gallery;

class Category extends PublicItem
{
    use TraitQueryPublic;

    public function getQuery()
    {
        return $this->getCategoryQuery();
    }

    public function getNameAll()
    {
        return 'categories';
    }

    public function getNameOne()
    {
        return 'category';
    }
}
