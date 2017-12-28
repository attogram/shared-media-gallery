<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class AdminPage
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitQueryAdmin;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct()
    {
        $this->accessControl();
    }

    public function pageList($data)
    {
        $this->data = $data;
        $this->setItems(PageQuery::create(), 'pages');
        $this->displayView('admin/page.list', $this->data);
    }

    public function pageSearch($data)
    {
        $this->data = $data;
        $this->adminSearch(new PageQuery());
        $this->displayView('admin/page.search', $this->data);
    }

    public function pageSave($data)
    {
        $this->data = $data;
        $this->adminSave(new PageQuery());
        $this->displayView('admin/page.save', $this->data);
    }
}
