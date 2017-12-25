<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\SiteQuery;

class AdminSite
{
    use TraitView;

    public function home($data)
    {
        $this->displayView('admin/home', $data);
    }

    public function site($data)
    {
        $data['site'] = SiteQuery::create()->findOneById(1);
        $this->displayView('admin/site', $data);
    }

    public function save($data)
    {
        print 'GalleryAdminSite::save';
    }
}
