<?php

namespace Attogram\SharedMedia\Gallery;

class Page
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
        $this->displayItems($this->getPageQuery(), 'pages');
    }

    public function getOne()
    {
        $this->displayItem($this->getPageQuery(), 'page');
    }
}
