<?php

namespace Attogram\SharedMedia\Gallery;

trait TraitPublicItem
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
        $this->displayItems($this->getQuery(), $this->nameAll);
    }

    public function getOne()
    {
        $this->displayItem($this->getQuery(), $this->nameOne);
    }
}
