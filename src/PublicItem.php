<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class PublicItem
 * @package Attogram\SharedMedia\Gallery
 */
class PublicItem
{
    use TraitErrors;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];

    /**
     * PublicItem constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getAll()
    {
        $this->displayItems($this->getQuery(), $this->getNameAll());
    }

    public function getOne()
    {
        $this->displayItem($this->getQuery(), $this->getNameOne());
    }
}
