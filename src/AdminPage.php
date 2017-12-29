<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class AdminPage
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitQueryAdmin;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        $this->setItems(PageQuery::create(), 'pages');
        $this->displayView('admin/page.list');
    }

    public function search()
    {
        $this->data['sourceSelect'] = $this->getSourcePulldown();
        $this->adminSearch(new PageQuery());
        $this->displayView('admin/page.search');
    }

    public function save()
    {
        $this->adminSave(new PageQuery());
        $this->displayView('admin/page.save');
    }
}
