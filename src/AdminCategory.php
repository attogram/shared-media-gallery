<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;

class AdminCategory
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitErrors;
    use TraitQueryAdmin;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function categoryList($data)
    {
        $this->data = $data;
        $this->accessControl();
        $this->setItems(CategoryQuery::create(), 'categories', 100);
        $this->displayView('admin/category.list', $this->data);
    }

    public function categorySave($data)
    {
        $this->data = $data;
        $this->accessControl();
        $this->adminSave(new CategoryQuery());
        $this->displayView('admin/category.save', $this->data);
    }

    public function categorySearch($data)
    {
        $this->data = $data;
        $this->accessControl();
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category.search', $this->data);
    }

    public function categorySubcats($data)
    {
        $this->data = $data;
        $this->setFromApi(
            new CategoryQuery(),
            'subcats',
            'subcats'
        );
        $this->displayView('admin/category.subcats', $this->data);
    }

    public function categoryMedia($data)
    {
        $this->data = $data;
        $this->setFromApi(
            new MediaQuery(),
            'getMediaInCategory',
            'medias'
        );
        $this->displayView('admin/category.media', $this->data);
    }

    private function setFromApi($orm, $method, $itemName)
    {
        $this->accessControl();
        $this->setCategoryId();
        $orm->setApiPageid($this->data['categoryId']);
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
