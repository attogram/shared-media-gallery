<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Site;
use Attogram\SharedMedia\Orm\SiteQuery;
use Exception;
use Propel\Runtime\Map\TableMap;

class AdminSite
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitErrors;
    use TraitTools;
    use TraitView;

    public function __construct()
    {
        $this->accessControl();
    }

    public function home($data)
    {
        $this->displayView('admin/home', $data);
    }

    public function settings($data)
    {
        $data['site'] = $this->getSiteData()->toArray(TableMap::TYPE_FIELDNAME);
        $this->displayView('admin/site.settings', $data);
    }

    public function saveSettings($data)
    {
        if (!$this->isPost()) {
            $this->error403('POST ONLY');
        }
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
        $this->displayView('admin/site.settings', $data);
    }

    /**
     * @return Site|null
     */
    private function getSiteData()
    {
        try {
            $site = SiteQuery::create()->findOneById(1);
        } catch (Exception $error) {
            $site = new Site();
        }
        if (!$site instanceof Site) {
            $site = new Site();
            $site->setTitle('Shared Media Gallery')->save();
        }
        return $site;
    }

    public function database($data)
    {
        $this->displayView('admin/site.database', $data);
    }
}
