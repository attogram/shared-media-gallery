<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Gallery\Seeder;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;

class GalleryAdmin
{
    use TraitView;

    private $data = [];

    /**
     * @param array $data
     */
    public function home($data)
    {
        $this->displayView('admin/home', $data);
    }

    public function media($data)
    {
        $this->data = $data;
        $this->adminSearch(new MediaQuery());
        $this->displayView('admin/media', $this->data);
    }

    public function mediaSave($data)
    {
        $this->data = $data;
        $this->adminSave(new MediaQuery());
        $this->displayView('admin/media.save', $this->data);
    }

    public function category($data)
    {
        $this->data = $data;
        $limit = Tools::getGet('limit');
        if (!$limit || !Tools::isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category', $this->data);
    }

    public function categorySave($data)
    {
        $this->data = $data;
        $this->adminSave(new CategoryQuery());
         $this->displayView('admin/category.save', $this->data);
    }

    public function page($data)
    {
        $this->data = $data;
        $this->adminSearch(new PageQuery());
        $this->displayView('admin/page', $this->data);
    }

    public function pageSave($data)
    {
        $this->data = $data;
        $this->adminSave(new PageQuery());
        $this->displayView('admin/page.save', $this->data);
    }


    public function source($data)
    {
        $seeder = new Seeder();
        $seeder->seedSources();
        foreach (SourceQuery::create()->find() as $source) {
            $data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
        }
        $this->displayView('admin/source', $data);
    }

    /**
     * @param object $api
     * @return void
     */
    private function adminSearch($api)
    {
        $query = Tools::getGet('q');
        if (!$query) {
            return;
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
    }

    /**
     * @param object $api
     * @return void
     */
    protected function adminSave($api)
    {
        $pageids = Tools::getPost('pageid');
        if (!$pageids) {
            return;
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
