<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Base as ApiBase;
use Attogram\SharedMedia\Orm\Category;
use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;

class AdminCategory
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitErrors;
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
        $this->setItems($this->getCategoryQuery(), 'categories', 100);
        $this->displayView('admin/category.list');
    }

    public function save()
    {
        $pageids = $this->getPost('pageid');
        if (empty($pageids) || !is_array($pageids)) {
            $this->error404('404 Category Not Selected');
        }
        $sourceId = $this->getPost('source_id');
        if (!$sourceId) {
            $this->error404('404 Source Not Found');
        }
        $title = $this->getPost('title');
        $files = $this->getPost('files');
        $subcats = $this->getPost('subcats');
        $pages = $this->getPost('pages');
        $size = $this->getPost('size');
        $hidden = $this->getPost('hidden');
        foreach ($pageids as $pageid) {
            $values = [
                'title' => $title[$pageid],
                'files' => $files[$pageid],
                'subcats' => $subcats[$pageid],
                'pages' => $pages[$pageid],
                'size' => $size[$pageid],
                'hidden' => $hidden[$pageid],
            ];
            $exists = CategoryQuery::create()
                ->filterBySourceId($sourceId)
                ->filterByPageid($pageid)
                ->findOne();
            if ($exists instanceof Category) {
                $exists = $this->setCategoryValues($exists, $values);
                $exists->save();
                continue;
            }
            $orm = new Category();
            $orm = $this->setCategoryValues($orm, $values);
            $orm->setPageid($pageid)
                ->setSourceId($sourceId)
                ->save();
        }
        $this->redirect301($this->data['uriBase'] . '/admin/category/list/');
    }

    /**
     * @param  \Attogram\SharedMedia\Orm\Category $orm
     * @param  array $values
     * @return \Attogram\SharedMedia\Orm\Category
     */
    private function setCategoryValues($orm, $values)
    {
        return $orm
            ->setTitle($values['title'])
            ->setFiles($values['files'])
            ->setSubcats($values['subcats'])
            ->setPages($values['pages'])
            ->setSize($values['size'])
            ->setHidden($values['hidden']);
    }

    public function search()
    {
        $limit = $this->getGet('limit');
        if (!$limit || !$this->isNumber($limit)) {
            $limit = ApiBase::DEFAULT_LIMIT;
        }
        $this->data['limit'] = $limit;
        $this->adminSearch(new CategoryQuery());
        $this->displayView('admin/category.search');
    }

    public function subcats()
    {
        $this->setCategoryId();
        $this->setFromApi(new CategoryQuery(), $this->data['categoryId'], 'subcats', 'subcats');
        $this->displayView('admin/category.subcats');
    }

    public function media()
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
