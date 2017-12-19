<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Seeder;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;

class GalleryAdmin extends Base
{
    const VERSION = '0.0.18';

    /**
     * @param int $level
     */
    public function __construct(int $level = 0)
    {
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
            'admin/page'          => ['page'],
            'admin/page.save'     => ['page', 'save'],
            'admin/source'        => ['source'],
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
        $limit = Tools::getGet('limit');
        if (!$limit || !Tools::isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
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
     * @return bool
     */
    protected function controlAdminPage()
    {
        return $this->adminSearch(new PageQuery());
    }

    /**
     * @return bool
     */
    protected function controlAdminPagesave()
    {
        return $this->adminSave(new PageQuery());
    }

    /**
     * @return bool
     */
    protected function controlAdminSource()
    {
        $seeder = new Seeder();
        $seeder->seedSources();
        foreach (SourceQuery::create()->find() as $source) {
            $this->data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
        }
        return true;
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
        $this->data['query'] = $query;
        $limit = Tools::getGet('limit');
        if (Tools::isNumber($limit) && $limit) {
            $api->setApiLimit($limit);
            $this->data['limit'] = $limit;
        }
        foreach ($api->search($query) as $result) {
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
        }
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
            try {
                $result->setSourceId(1);
                $this->adminSaveOrUpdate($api, $result);
            } catch (PropelException $error) {
                $this->data['errors'][] = 'adminSave: pageid:'
                    . $result->getPageid() . ': ' . $error->getMessage();
            }
        }
        return true;
    }

    /**
     * @param object $api
     * @param object $result
     * @return void
     */
    private function adminSaveOrUpdate($api, $result)
    {
        $exists = $api::create()
            ->filterByPageid($result->getPageid())
            ->findOne();
        if (!$exists) {
            $result->save();
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
            return;
        }
        $exists->fromArray($result->toArray());
        $exists->save();
        $this->data['results'][] = $exists->toArray(TableMap::TYPE_FIELDNAME);
    }
}
