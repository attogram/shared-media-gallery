<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class Media
 * @package Attogram\SharedMedia\Gallery
 */
class Media extends PublicItem
{
    use TraitQueryPublic;

    /**
     * @return \Attogram\SharedMedia\Orm\MediaQuery;
     */
    public function getQuery()
    {
        return $this->getMediaQuery();
    }

    /**
     * @return string
     */
    public function getNameAll()
    {
        return 'medias';
    }

    /**
     * @return string
     */
    public function getNameOne()
    {
        return 'media';
    }
}
