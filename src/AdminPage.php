<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class AdminPage
{
    use TraitQueryAdmin;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function pageList($data)
    {
        $this->data = $data;
        $this->setItems(PageQuery::create(), 'pages');
        $this->displayView('admin/page.list', $this->data);
    }

    public function pageFind($data)
    {
        $this->data = $data;
        $this->adminSearch(new PageQuery());
        $this->displayView('admin/page.find', $this->data);
    }

    public function pageSave($data)
    {
        $this->data = $data;
        $this->adminSave(new PageQuery());
        $this->displayView('admin/page.save', $this->data);
    }
}
