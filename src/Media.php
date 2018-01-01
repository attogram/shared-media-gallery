<?php

namespace Attogram\SharedMedia\Gallery;

class Media
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
        $this->displayItems($this->getMediaQuery(), 'medias');
    }

    public function getOne()
    {
        $this->displayItem($this->getMediaQuery(), 'media');
    }
}
