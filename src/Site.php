<?php

namespace Attogram\SharedMedia\Gallery;

use Propel\Runtime\Map\TableMap;
use Throwable;

/**
 * Class Site
 * @package Attogram\SharedMedia\Gallery
 */
class Site
{
    use TraitErrors;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];

    /**
     * Site constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function home()
    {
        try {
            /** @var \Attogram\SharedMedia\Orm\Media $media */
            $media = $this->getMediaQuery()
                ->setOffset(rand(1, $this->data['media_count'] - 1))
                ->findOne();
            $this->data['media'] = $media->toArray(TableMap::TYPE_FIELDNAME);
        } catch (Throwable $error) {
            print $error->getMessage();
        }
        $this->displayView('home');
    }

    public function about()
    {
        $this->displayView('about');
    }
}
