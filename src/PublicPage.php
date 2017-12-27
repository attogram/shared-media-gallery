<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class PublicPage
{
    use TraitErrors;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    public function pages($data)
    {
        $this->displayItems($data, PageQuery::create(), 'pages');
    }

    public function page($data)
    {
        $this->displayItem($data, PageQuery::create(), 'page');
    }
}
