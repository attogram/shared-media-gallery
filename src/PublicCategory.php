<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;

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
        $this->displayItem(CategoryQuery::create(), 'category');
    }
}
