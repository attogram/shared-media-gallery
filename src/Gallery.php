<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\Router\Router;

class Gallery
{
    use TraitCounts;
    use TraitDatabase;
    use TraitErrors;
    use TraitView;

    const VERSION = '0.1.7';

    private $router;
    private $data = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->setPublicRoutes();
        $this->setAdminRoutes();
        $control = $this->router->match(); // get controller
        if (!$control) {
            $this->error404();
        }
        list($className, $methodName) = explode('::', $control);
        $className = 'Attogram\\SharedMedia\\Gallery\\' . $className;
        if (!is_callable([$className, $methodName])) {
            $this->error404('404 Control Not Found');
        }
        $this->setupDatabase();
        $this->setData();
        $class = new $className($this->data); // instantiate controller class
        $class->{$methodName}(); // call controller method
    }

    /**
     * @return void
     */
    private function setPublicRoutes()
    {
        $this->router->allow('/', 'PublicSite::home');
        $this->router->allow('/about/', 'PublicSite::about');
        $this->router->allow('/category/', 'PublicCategory::categories');
        $this->router->allow('/category/?/', 'PublicCategory::category');
        $this->router->allow('/media/', 'PublicMedia::medias');
        $this->router->allow('/media/?/', 'PublicMedia::media');
        $this->router->allow('/page/', 'PublicPage::pages');
        $this->router->allow('/page/?/', 'PublicPage::page');
    }

    /**
     * @return void
     */
    private function setAdminRoutes()
    {
        // Site Admin Routes
        $this->router->allow('/admin/', 'AdminSite::home');
        $this->router->allow('/admin/site/settings/', 'AdminSite::settings');
        $this->router->allow('/admin/site/settings/save/', 'AdminSite::saveSettings');
        $this->router->allow('/admin/site/database/', 'AdminSite::database');
        // Category Admin Routes
        $this->router->allow('/admin/category/list/', 'AdminCategory::categoryList');
        $this->router->allow('/admin/category/search/', 'AdminCategory::categorySearch');
        $this->router->allow('/admin/category/save/', 'AdminCategory::categorySave');
        $this->router->allow('/admin/category/?/media/', 'AdminCategory::categoryMedia');
        $this->router->allow('/admin/category/?/subcats/', 'AdminCategory::categorySubcats');
        // Media Admin Routes
        $this->router->allow('/admin/media/list/', 'AdminMedia::mediaList');
        $this->router->allow('/admin/media/search/', 'AdminMedia::mediaSearch');
        $this->router->allow('/admin/media/save/', 'AdminMedia::mediaSave');
        $this->router->allow('/admin/media/?/categories/', 'AdminMedia::mediaCategories');
        // Page Admin Routes
        $this->router->allow('/admin/page/list/', 'AdminPage::pageList');
        $this->router->allow('/admin/page/search/', 'AdminPage::pageSearch');
        $this->router->allow('/admin/page/save/', 'AdminPage::pageSave');
        // Source Admin Routes
        $this->router->allow('/admin/source/list/', 'AdminSource::list');
        $this->router->allow('/admin/source/save/', 'AdminSource::save');
        $this->router->allow('/admin/source/?/delete/', 'AdminSource::delete');
        $this->router->allow('/admin/source/?/edit/', 'AdminSource::edit');
    }

    /**
     * @return void
     */
    private function setData()
    {
        $this->data['title'] = 'Shared Media Gallery';
        $this->data['version'] = self::VERSION;
        $this->data['uriBase'] = $this->router->getUriBase();
        $this->data['uriRelative'] = $this->router->getUriRelative();
        if (!empty($this->router->getVars())) {
            $this->data['vars'] = $this->router->getVars();
        }
        $this->setCounts(); // set counts for: category, media, page, source
    }
}
