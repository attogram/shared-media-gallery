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

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function mediaList()
    {
        $orm = MediaQuery::create()
            ->joinWith('Source')
            ->withColumn('source.title');
        $this->setItems($orm, 'medias');
        $this->displayView('admin/media.list');
    }

    public function mediaSearch()
    {
        $this->adminSearch(new MediaQuery());
        $this->displayView('admin/media.search');
    }

    public function mediaSave()
    {
        $this->adminSave(new MediaQuery());
        $this->displayView('admin/media.save');
    }

    public function mediaCategories()
    {
        $this->displayView('admin/media.categories');
    }
}
