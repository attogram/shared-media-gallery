<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\CategoryQuery;

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
        $this->accessControl();
        $categoryId = (int) $data['vars'][0];
        if (!$categoryId || !$this->isNumber($categoryId)) {
            $this->error404('Category Not Found');
        }

        $category = new CategoryQuery();
        $category->setApiPageid($categoryId);
        $category->setApiLimit(500);

        $subcats = $category->subcats();

        $this->data = $data;
        $this->data['subcats'] = $subcats;

        $this->displayView('admin/category.subcats', $this->data);
    }
}
