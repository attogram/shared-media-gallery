<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class AdminPage
 * @package Attogram\SharedMedia\Gallery
 */
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
    private $fieldNames = [];

    /**
     * AdminPage constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        $this->setItems($this->getPageQuery(), 'pages');
        $this->displayView('admin/page.list');
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
            'title',
            'displaytitle',
            'page_image_free',
            'wikibase_item',
            'disambiguation',
            'defaultsort',
        ];
    }

    public function search()
    {
        $this->adminSearch($this->getPageQuery());
        $this->displayView('admin/page.search');
    }
}
