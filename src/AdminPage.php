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

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function pageList()
    {
        $this->setItems(PageQuery::create(), 'pages');
        $this->displayView('admin/page.list');
    }

    public function pageSearch()
    {
        $this->adminSearch(new PageQuery());
        $this->displayView('admin/page.search');
    }

    public function pageSave()
    {
        $this->adminSave(new PageQuery());
        $this->displayView('admin/page.save');
    }
}
