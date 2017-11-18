<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Exception\RuntimeException;

class GalleryTools
{
    const VERSION = '0.0.3';

    /**
     * @param array|null $data
     */
    public function setup(array $data = [])
    {
        $data['media_count'] = $this->getMediaCount();
        $data['category_count'] = $this->getCategoryCount();
        return $data;
    }

    public function getCategoryCount()
    {
        try {
            return CategoryQuery::create()->count();
        } catch (RuntimeException $error) {
            print '<pre>ERROR: getCategoryCount: ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }

    public function getMediaCount()
    {
        try {
            return MediaQuery::create()->count();
        } catch (RuntimeException $error) {
            print '<pre>ERROR: getMediaCount: ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }
}
