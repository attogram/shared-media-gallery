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

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function categoryList()
    {
        $orm = CategoryQuery::create()
            ->joinWith('Source')
            ->withColumn('source.title');
        $this->setItems($orm, 'categories', 100);
        $this->displayView('admin/category.list');
    }

    public function categorySave()
    {
        $this->adminSave(new CategoryQuery());
        $this->displayView('admin/category.save');
    }

    public function categorySearch()
    {
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category.search');
    }

    public function categorySubcats()
    {
        $this->setCategoryId();
        $this->setFromApi(new CategoryQuery(), $this->data['categoryId'], 'subcats', 'subcats');
        $this->displayView('admin/category.subcats');
    }

    public function categoryMedia()
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
