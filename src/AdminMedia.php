<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;

class AdminMedia
{
    use TraitQueryAdmin;
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function mediaList($data)
    {
        $this->data = $data;
        $this->setItems(MediaQuery::create(), 'medias');
        $this->displayView('admin/media.list', $this->data);
    }

    public function mediaFind($data)
    {
        $this->data = $data;
        $this->adminSearch(new MediaQuery());
        $this->displayView('admin/media.find', $this->data);
    }

    public function mediaSave($data)
    {
        $this->data = $data;
        $this->adminSave(new MediaQuery());
        $this->displayView('admin/media.save', $this->data);
    }
}
