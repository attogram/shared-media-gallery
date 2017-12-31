<?php

namespace Attogram\SharedMedia\Gallery;

class AdminCategory
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

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        $this->setItems($this->getCategoryQuery(), 'categories', 100);
        $this->displayView('admin/category.list');
    }

    public function save()
    {
        $this->setFieldNames();
        $this->adminSave('Attogram\\SharedMedia\\Orm\\Category');
        $this->redirect301($this->data['uriBase'] . '/admin/category/list/');
    }

    private function setFieldNames()
    {
        $this->fieldNames = [
            'title',
            'files',
            'subcats',
            'pages',
            'size',
            'hidden',
        ];
    }

    public function search()
    {
        $this->adminSearch($this->getCategoryQuery());
        $this->displayView('admin/category.search');
    }

    public function subcats()
    {
        $this->setCategoryId();
        $this->setFromApi(
            $this->getCategoryQuery(),
            $this->data['categoryId'],
            'subcats',
            'subcats'
        );
        $this->displayView('admin/category.subcats');
    }

    public function media()
    {
        $this->setCategoryId();
        $this->setFromApi(
            $this->getMediaQuery(),
            $this->data['categoryId'],
            'getMediaInCategory',
            'medias'
        );
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
