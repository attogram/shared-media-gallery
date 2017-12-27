<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\Router\Router;

class Gallery
{
    use TraitCounts;
    use TraitDatabase;
    use TraitErrors;
    use TraitView;

    const VERSION = '0.1.5';

    private $router;
    private $data = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->setRoutes();
        $control = $this->router->match(); // get controller
        $this->data['uriBase'] = $this->router->getUriBase();
        $this->data['title'] = 'Shared Media Gallery';
        $this->data['version'] = self::VERSION;
        if (!$control) {
            $this->error404();
        }
        list($className, $methodName) = explode('::', $control);
        $className = 'Attogram\\SharedMedia\\Gallery\\' . $className;
        if (!is_callable([$className, $methodName])) {
            $this->error404('Control Not Found');
        }
        $this->setupDatabase();
        $this->data['media_count'] = $this->getMediaCount();
        $this->data['category_count'] = $this->getCategoryCount();
        $this->data['page_count'] = $this->getPageCount();
        $this->data['source_count'] = $this->getSourceCount();
        $this->data['uriRelative'] = $this->router->getUriRelative();
        $this->data['vars'] = $this->router->getVars();
        $class = new $className;
        $class->{$methodName}($this->data); // call controller
    }

    /**
     * @return void
     */
    private function setRoutes()
    {
        // Public Gallery Routes
        $this->router->allow('/', 'PublicSite::home');
        $this->router->allow('/about/', 'PublicSite::about');
        $this->router->allow('/category/', 'PublicCategory::categories');
        $this->router->allow('/category/?/', 'PublicCategory::category');
        $this->router->allow('/media/', 'PublicMedia::medias');
        $this->router->allow('/media/?/', 'PublicMedia::media');
        $this->router->allow('/page/', 'PublicPage::pages');
        $this->router->allow('/page/?/', 'PublicPage::page');
        // Site Admin Routes
        $this->router->allow('/admin/', 'AdminSite::home');
        $this->router->allow('/admin/site/settings/', 'AdminSite::settings');
        $this->router->allow('/admin/site/settings/save/', 'AdminSite::saveSettings');
        $this->router->allow('/admin/site/database/', 'AdminSite::database');
        // Category Admin Routes
        $this->router->allow('/admin/category/list/', 'AdminCategory::categoryList');
        $this->router->allow('/admin/category/find/', 'AdminCategory::categoryFind');
        $this->router->allow('/admin/category/save/', 'AdminCategory::categorySave');
        $this->router->allow('/admin/category/?/subcats/', 'AdminCategory::categorySubcats');
        // Media Admin Routes
        $this->router->allow('/admin/media/list/', 'AdminMedia::mediaList');
        $this->router->allow('/admin/media/find/', 'AdminMedia::mediaFind');
        $this->router->allow('/admin/media/save/', 'AdminMedia::mediaSave');
        // Page Admin Routes
        $this->router->allow('/admin/page/list/', 'AdminPage::pageList');
        $this->router->allow('/admin/page/find/', 'AdminPage::pageFind');
        $this->router->allow('/admin/page/save/', 'AdminPage::pageSave');
        // Source Admin Routes
        $this->router->allow('/admin/source/', 'AdminSource::source');
        $this->router->allow('/admin/source/save', 'AdminSource::save');
    }
}
