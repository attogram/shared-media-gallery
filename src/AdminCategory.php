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
    private $values = [];

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
            $this->values = [
                'title' => $title[$pageid],
                'files' => $files[$pageid],
                'subcats' => $subcats[$pageid],
                'pages' => $pages[$pageid],
                'size' => $size[$pageid],
                'hidden' => $hidden[$pageid],
            ];
            if ($this->updateCategoryIfExists($sourceId, $pageid)) {
                continue;
            }
            $this->setCategoryValues(new Category())
                ->setSourceId($sourceId)
                ->setPageid($pageid)
                ->save();
        }
        $this->redirect301($this->data['uriBase'] . '/admin/category/list/');
    }

    /**
     * @param int $sourceId
     * @param int $pageid
     * @return bool
     */
    private function updateCategoryIfExists($sourceId, $pageid)
    {
		$orm = CategoryQuery::create()
			->filterBySourceId($sourceId)
			->filterByPageid($pageid)
			->findOne();
		if (!$orm instanceof Category) {
			return false;
		}
		$this->setCategoryValues($orm)
			->save();
        return true;
    }

    /**
     * @param object $orm
     * @return object
     */
    private function setCategoryValues($orm)
    {
        return $orm
            ->setTitle($this->values['title'])
            ->setFiles($this->values['files'])
            ->setSubcats($this->values['subcats'])
            ->setPages($this->values['pages'])
            ->setSize($this->values['size'])
            ->setHidden($this->values['hidden']);
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
