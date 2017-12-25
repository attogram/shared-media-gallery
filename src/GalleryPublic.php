<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Attogram\SharedMedia\Orm\PageQuery;
use Exception;

class GalleryPublic
{
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    const ITEMS_PER_PAGE = 10;

    private $data = [];

    /**
     * @param array $data
     */
    public function home($data)
    {
        try {
            $data['media'] = MediaQuery::create()
                ->setOffset(rand(1, $data['category_count'] - 1))
                ->findOne();
        } catch (Exception $error) {
            $data['media'] = ['title' => 'Error', 'pageid' => 0];
        }
        $this->displayView('home', $data);
    }

    public function about($data)
    {
        $this->displayView('about', $data);
    }

    public function medias($data)
    {
        $this->displayItems($data, MediaQuery::create(), 'medias');
    }

    public function categories($data)
    {
        $this->displayItems($data, CategoryQuery::create(), 'categories', 100);
    }

    public function pages($data)
    {
        $this->displayItems($data, PageQuery::create(), 'pages');
    }

    /**
     * @param array    $data
     * @param object   $orm
     * @param string   $name
     * @param int      $limit
     */
    private function displayItems($data, $orm, $name, $limit = 0)
    {
        $this->data = $data;
        $orm = $this->setupSearch($orm);
        $this->setItems($orm, $name, $limit);
        $this->displayView($name, $this->data);
    }

    public function media($data)
    {
        $this->displayItem($data, MediaQuery::create(), 'media');
    }

    public function category($data)
    {
        $this->displayItem($data, CategoryQuery::create(), 'category');
    }

    public function page($data)
    {
        $this->displayItem($data, PageQuery::create(), 'page');
    }

    /**
     * @param array    $data
     * @param object   $orm
     * @param string   $name
     */
    private function displayItem($data, $orm, $name)
    {
        $this->data = $data;
        if (!$this->setItem($orm, $name)) {
            return;
        }
        $this->displayView($name, $this->data);
    }
}
