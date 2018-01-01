<?php

namespace Attogram\SharedMedia\Gallery;

class Media extends PublicItem
{
    use TraitQueryPublic;

    public function getQuery()
    {
        return $this->getMediaQuery();
    }

    public function getNameAll()
    {
        return 'medias';
    }

    public function getNameOne()
    {
        return 'media';
    }
}
