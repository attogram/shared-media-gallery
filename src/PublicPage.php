<?php

namespace Attogram\SharedMedia\Gallery;

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
        $this->displayItems($this->getPageQuery(), 'pages');
    }

    public function page()
    {
        $this->displayItem($this->getPageQuery(), 'page');
    }
}
