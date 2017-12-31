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

    public function medias()
    {
        $this->displayItems($this->getMediaQuery(), 'medias');
    }

    public function media()
    {
        $this->displayItem($this->getMediaQuery(), 'media');
    }
}
