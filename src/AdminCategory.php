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
        $this->accessControl();
        $this->data = $data;
        $this->setItems(CategoryQuery::create(), 'categories', 100);
        $this->displayView('admin/category.list', $this->data);
    }

    public function categoryFind($data)
    {
        $this->accessControl();
        $this->data = $data;
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category.find', $this->data);
    }

    public function categorySave($data)
    {
        $this->accessControl();
        $this->data = $data;
        $this->adminSave(new CategoryQuery());
        $this->displayView('admin/category.save', $this->data);
    }

    public function categorySubcats($data)
    {
        $this->getFromApi(
            $data,
            new CategoryQuery(),
            'subcats',
            'subcats',
            'admin/category.subcats'
        );
    }

    public function categoryMedia($data)
    {
        $this->getFromApi(
            $data,
            new MediaQuery(),
            'getMediaInCategory',
            'medias',
            'admin/category.media'
        );
    }

    private function getFromApi($data, $orm, $call, $dataName, $view)
    {
        $this->accessControl();
        $this->data = $data;
        $this->setCategoryId();
        $orm->setApiPageid($this->data['categoryId']);
        $orm->setApiLimit(50);
        $this->data[$dataName] = $orm->{$call}();
        $this->displayView($view, $this->data);
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
