<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\CategoryQuery;

class AdminCategory
{
    use TraitAccessControl;
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
}
