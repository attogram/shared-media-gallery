<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;

class PublicMedia
{
    use TraitErrors;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    public function medias($data)
    {
        $this->displayItems($data, MediaQuery::create(), 'medias');
    }

    public function media($data)
    {
        $this->displayItem($data, MediaQuery::create(), 'media');
    }
}
