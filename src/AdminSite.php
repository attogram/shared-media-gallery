<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Site;
use Attogram\SharedMedia\Orm\SiteQuery;
use Propel\Runtime\Map\TableMap;

class AdminSite
{
    use TraitAccessControl;
    use TraitTools;
    use TraitView;

    public function home($data)
    {
        $this->accessControl();
        $this->displayView('admin/home', $data);
    }

    public function site($data)
    {
        $this->accessControl();
        $data['site'] = $this->getSiteData()->toArray(TableMap::TYPE_FIELDNAME);
        $this->displayView('admin/site', $data);
    }

    public function save($data)
    {
        $this->accessControl();
        $data['title'] = $this->getPost('title');
        $data['about'] = $this->getPost('about');
        $data['header'] = $this->getPost('header');
        $data['footer'] = $this->getPost('footer');
        $data['cdn'] = $this->getPost('cdn') === 'on' ? true : false;
        $site = $this->getSiteData();
        $site->setTitle($data['title'])
            ->setAbout($data['about'])
            ->setHeader($data['header'])
            ->setFooter($data['footer'])
            ->setUseCdn($data['cdn'])
            ->save();
        $data['site'] = $site->toArray(TableMap::TYPE_FIELDNAME);
        $data['saved'] = true;
        $this->displayView('admin/site', $data);
    }

    /**
     * @return Site
     */
    private function getSiteData()
    {
        $site = SiteQuery::create()->findOneById(1);
        if (!$site instanceof Site) {
            $site = new Site();
            $site->setTitle('Shared Media Gallery')->save();
        }
        return $site;
    }
}
