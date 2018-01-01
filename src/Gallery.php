<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\Router\Router;

class Gallery
{
    use TraitCounts;
    use TraitErrors;
    use TraitView;

    const VERSION = '0.1.10';

    private $router;
    private $data = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->allowPublicRoutes();
        $this->allowAdminRoutes();
        $control = $this->router->match(); // get controller
        if (!$control) {
            $this->error404();
        }
        list($className, $methodName) = explode('::', $control);
        $className = 'Attogram\\SharedMedia\\Gallery\\' . $className;
        if (!is_callable([$className, $methodName])) {
            $this->error404('Controller Not Found');
        }
        $this->setupDatabase();
        $this->setData();
        $class = new $className($this->data); // instantiate controller class
        $class->{$methodName}(); // call controller method
    }

    /**
     * @return void
     */
    private function allowPublicRoutes()
    {
        $this->router->allow('/', 'Site::home');
        $this->router->allow('/about/', 'Site::about');
        $this->router->allow('/category/', 'Category::getAll');
        $this->router->allow('/category/?/', 'Category::getOne');
        $this->router->allow('/media/', 'Media::getAll');
        $this->router->allow('/media/?/', 'Media::getOne');
        $this->router->allow('/page/', 'Page::getAll');
        $this->router->allow('/page/?/', 'Page::getOne');
    }

    /**
     * @return void
     */
    private function allowAdminRoutes()
    {
        // Site Admin
        $this->router->allow('/admin/', 'AdminSite::home');
        $this->router->allow('/admin/site/settings/', 'AdminSite::settings');
        $this->router->allow('/admin/site/settings/save/', 'AdminSite::saveSettings');
        // Category Admin
        $this->router->allow('/admin/category/list/', 'AdminCategory::list');
        $this->router->allow('/admin/category/search/', 'AdminCategory::search');
        $this->router->allow('/admin/category/save/', 'AdminCategory::save');
        $this->router->allow('/admin/category/?/media/', 'AdminCategory::media');
        $this->router->allow('/admin/category/?/subcats/', 'AdminCategory::subcats');
        // Media Admin
        $this->router->allow('/admin/media/list/', 'AdminMedia::list');
        $this->router->allow('/admin/media/search/', 'AdminMedia::search');
        $this->router->allow('/admin/media/save/', 'AdminMedia::save');
        $this->router->allow('/admin/media/?/categories/', 'AdminMedia::categories');
        // Page Admin
        $this->router->allow('/admin/page/list/', 'AdminPage::list');
        $this->router->allow('/admin/page/search/', 'AdminPage::search');
        $this->router->allow('/admin/page/save/', 'AdminPage::save');
        // Source Admin
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

    private function setupDatabase()
    {
        $dsn = 'sqlite:' . __DIR__ . '/../database/gallery.sq3';
        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'sqlite');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration([
          'dsn' => $dsn,
          'user' => 'root',
          'password' => '',
          'settings' => [
            'charset' => 'utf8',
            'queries' =>[],
          ],
          'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
          'model_paths' => [
            0 => 'vendor/attogram/shared-media-orm/src/Attogram/SharedMedia/Orm/',
          ],
        ]);
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');
    }
}
