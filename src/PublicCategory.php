<?php

namespace Attogram\SharedMedia\Gallery;

class PublicCategory
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

    public function categories()
    {
        $this->displayItems($this->getCategoryQuery(), 'categories', 250);
    }

    public function category()
    {
        $this->displayItem($this->getCategoryQuery(), 'category');
    }
}
