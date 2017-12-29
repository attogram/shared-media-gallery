<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class PublicPage
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

    public function pages()
    {
        $this->displayItems(PageQuery::create(), 'pages');
    }

    public function page()
    {
        $this->displayItem(PageQuery::create(), 'page');
    }
}
