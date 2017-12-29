<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Site;
use Attogram\SharedMedia\Orm\SiteQuery;
use Throwable;
use Propel\Runtime\Map\TableMap;

class AdminSite
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitErrors;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function home()
    {
        $this->displayView('admin/home');
    }

    public function settings()
    {
        $this->data['site'] = $this->getSiteData()->toArray(TableMap::TYPE_FIELDNAME);
        $this->displayView('admin/site.settings');
    }

    public function saveSettings()
    {
        if (!$this->isPost()) {
            $this->error403('POST ONLY');
        }
        $this->data['title'] = $this->getPost('title');
        $this->data['about'] = $this->getPost('about');
        $this->data['header'] = $this->getPost('header');
        $this->data['footer'] = $this->getPost('footer');
        $this->data['cdn'] = $this->getPost('cdn') === 'on' ? true : false;
        $site = $this->getSiteData();
        $site->setTitle($this->data['title'])
            ->setAbout($this->data['about'])
            ->setHeader($this->data['header'])
            ->setFooter($this->data['footer'])
            ->setUseCdn($this->data['cdn'])
            ->save();
        $this->data['site'] = $site->toArray(TableMap::TYPE_FIELDNAME);
        $this->data['saved'] = true;
        $this->displayView('admin/site.settings');
    }

    public function database()
    {
        $this->displayView('admin/site.database');
    }

    /**
     * @return Site|null
     */
    private function getSiteData()
    {
        try {
            $site = SiteQuery::create()->findOneById(1);
        } catch (Throwable $error) {
            $site = new Site();
        }
        if (!$site instanceof Site) {
            $site = new Site();
            $site->setTitle('Shared Media Gallery')->save();
        }
        return $site;
    }
}
