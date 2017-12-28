<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;

class AdminMedia
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

    public function mediaList($data)
    {
        $this->data = $data;
        $this->setItems(MediaQuery::create(), 'medias');
        $this->displayView('admin/media.list', $this->data);
    }

    public function mediaSearch($data)
    {
        $this->data = $data;
        $this->adminSearch(new MediaQuery());
        $this->displayView('admin/media.search', $this->data);
    }

    public function mediaSave($data)
    {
        $this->data = $data;
        $this->adminSave(new MediaQuery());
        $this->displayView('admin/media.save', $this->data);
    }

    public function mediaCategories($data)
    {
        $this->data = $data;
        $this->displayView('admin/media.categories', $this->data);
    }
}
