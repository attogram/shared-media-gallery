<?php

namespace Attogram\SharedMedia\Gallery;

class Category
{
    use TraitErrors;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getAll()
    {
        $this->displayItems($this->getCategoryQuery(), 'categories', 250);
    }

    public function getOne()
    {
        $this->displayItem($this->getCategoryQuery(), 'category');
    }
}
