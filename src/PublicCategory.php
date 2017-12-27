<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;

class PublicCategory
{
    use TraitErrors;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    public function categories($data)
    {
        $this->displayItems($data, CategoryQuery::create(), 'categories', 100);
    }

    public function category($data)
    {
        $this->displayItem($data, CategoryQuery::create(), 'category');
    }
}
