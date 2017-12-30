<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\PageQuery;

class AdminPage
{
    use TraitAccessControl;
    use TraitAdminSave;
    use TraitAdminSearch;
    use TraitEnvironment;
    use TraitErrors;
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
        $this->setFieldNames();
        $this->adminSave('Attogram\\SharedMedia\\Orm\\Page');
        $this->redirect301($this->data['uriBase'] . '/admin/page/list/');
    }

    private function setFieldNames()
    {
        $this->fieldNames = [
        ];
    }
}
