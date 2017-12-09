<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Exception\RuntimeException;

class GalleryTools
{
    const VERSION = '0.0.4';

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
        return $this->getCount(new CategoryQuery());
    }

    public function getMediaCount()
    {
        return $this->getCount(new MediaQuery());
    }

    /**
     * @param ojbect $orm
     */
    private function getCount($orm) {
        try {
            return $orm->count();
        } catch (RuntimeException $error) {
            print '<pre>ERROR: getCount: orm:' . get_class($orm) . ': ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }
}
