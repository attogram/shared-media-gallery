<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.12';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery Admin';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

    /**
     * @return array
     */
    protected function getRoutes()
    {
        return [
            // Template           => URI path
            'admin/home'          => [''],
            'admin/media'         => ['media'],
            'admin/media.save'    => ['media', 'save'],
            'admin/category'      => ['category'],
            'admin/category.save' => ['category', 'save'],
            'admin/debug'         => ['debug'],
        ];
    }

    /**
     * @return bool
     */
    protected function controlAdminMedia()
    {
        return $this->adminSearch(new MediaQuery());
    }

    /**
     * @return bool
     */
    protected function controlAdminMediasave()
    {
        return $this->adminSave(new MediaQuery());
    }

    /**
     * @return bool
     */
    protected function controlAdminCategory()
    {
        return $this->adminSearch(new CategoryQuery());
    }

    /**
     * @return bool
     */
    protected function controlAdminCategorysave()
    {
        return $this->adminSave(new CategoryQuery());
    }

    /**
     * @param object $api
     * @return bool
     */
    private function adminSearch($api)
    {
        $query = Tools::getGet('q');
        if (!$query) {
            return true;
        }
        foreach ($api->search($query) as $result) {
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
        }
        $this->data['query'] = $query;
        return true;
    }

    /**
     * @param object $api
     * @return bool
     */
    protected function adminSave($api)
    {
        $pageids = Tools::getPost('pageid');
        if (!$pageids) {
            return true;
        }
        if (is_array($pageids)) {
            $pageids = implode('|', $pageids);
        }
        $api->setApiPageid($pageids);
        foreach ($api->info() as $result) {
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
            // @TODO - if in db, then update, else save new
            try {
                $result->save();
            } catch (PropelException $error) {
                print '<pre>ERROR: pageid:' . $result->getPageid()
                . ': ' . $error->getMessage() . '</pre>';
            }
        }
        $this->data['pageids'] = $pageids;
        return true;
    }
}
