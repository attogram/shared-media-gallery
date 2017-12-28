<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;

class PublicCategory
{
    use TraitErrors;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function categories()
    {
        $this->displayItems(CategoryQuery::create(), 'categories', 100);
    }

    public function category()
    {
        $this->displayItem(CategoryQuery::create(), 'category');
    }
}
