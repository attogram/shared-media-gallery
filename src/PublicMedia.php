<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;

class PublicMedia
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

    public function medias()
    {
        $this->displayItems(MediaQuery::create(), 'medias');
    }

    public function media()
    {
        $this->displayItem(MediaQuery::create(), 'media');
    }
}
