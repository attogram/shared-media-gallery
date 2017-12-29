<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\Category;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;

class AdminCategory
{
    use TraitAccessControl;
    use TraitAdminSave;
    use TraitEnvironment;
    use TraitErrors;
    use TraitQueryAdmin;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];
    private $values = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        $this->setItems($this->getCategoryQuery(), 'categories', 100);
        $this->displayView('admin/category.list');
    }

    public function save()
    {
        $this->setPostVars();
        foreach ($this->pageids as $pageid) {
            foreach ($this->getFieldNames() as $field) {
                $this->values[$field] = $this->{$field}[$pageid];
            }
            if (!$this->updateCategoryIfExists($pageid)) {
                $this->setValues(new Category())
                    ->setSourceId($this->sourceId)
                    ->setPageid($pageid)
                    ->save();
            }
        }
        $this->redirect301($this->data['uriBase'] . '/admin/category/list/');
    }

    private function getFieldNames()
    {
        return [
            'title',
            'files',
            'subcats',
            'pages',
            'size',
            'hidden',
        ];
    }

    /**
     * @param int $pageid
     * @return bool
     */
    private function updateCategoryIfExists($pageid)
    {
        $orm = CategoryQuery::create()
            ->filterBySourceId($this->sourceId)
            ->filterByPageid($pageid)
            ->findOne();
        if (!$orm instanceof Category) {
            return false;
        }
        $this->setValues($orm)
            ->save();
        return true;
    }

    public function search()
    {
        $this->data['sourceSelect'] = $this->getSourcePulldown();
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category.search');
    }

    public function subcats()
    {
        $this->setCategoryId();
        $this->setFromApi(new CategoryQuery(), $this->data['categoryId'], 'subcats', 'subcats');
        $this->displayView('admin/category.subcats');
    }

    public function media()
    {
        $this->setCategoryId();
        $this->setFromApi(new MediaQuery(), $this->data['categoryId'], 'getMediaInCategory', 'medias');
        $this->displayView('admin/category.media');
    }

    private function setFromApi($orm, $pageid, $method, $itemName)
    {
        $orm->setApiPageid($pageid);
        $orm->setApiLimit(50);
        $this->data[$itemName] = $orm->{$method}();
    }

    private function setCategoryId()
    {
        if (!isset($this->data['vars'][0])) {
            $this->error404('Category Not Found');
        }
        $this->data['categoryId'] = (int) $this->data['vars'][0];
        if (!$this->data['categoryId'] || !$this->isNumber($this->data['categoryId'])) {
            $this->error404('Category Not Found');
        }
    }
}
