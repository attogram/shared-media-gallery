<?php

namespace Attogram\SharedMedia\Gallery;

class Media
{
    use TraitPublicItem;

    private $nameAll = 'medias';
    private $nameOne = 'media';

    private function getQuery()
    {
        return $this->getMediaQuery();
    }
}
