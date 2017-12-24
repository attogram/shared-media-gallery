<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\Router\Router;
use Attogram\SharedMedia\Gallery\Tools;

class Gallery
{
    use TraitDatabase;
    use TraitView;

    const VERSION = '0.1.0';

    private $router;

    protected $galleryTools;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setupDatabase();
        $this->galleryTools = new GalleryTools;
        $data = $this->galleryTools->setup([]); // set counts
        $this->router = new Router();
        $this->setRoutes();
        $control = $this->router->match(); // get controller
        $data['uriBase'] = $this->router->getUriBase();
        $data['uriRelative'] = $this->router->getUriRelative();
        $data['vars'] = $this->router->getVars();
        $data['title'] = 'Shared Media Gallery';
        $data['version'] = self::VERSION;
        if (!$control) {
            $this->error404('404 Page Not Found');
            return;
        }
        list($className, $methodName) = explode('::', $control);
        $className = 'Attogram\\SharedMedia\\Gallery\\' . $className;
        if (!is_callable([$className, $methodName])) {
            $this->error404('404 Control Not Found');
            return;
        }
        $class = new $className;
        $class->{$methodName}($data); // call controller
    }

    /**
     * @return void
     */
    protected function setRoutes()
    {
        // Public Routes
        $this->router->allow('/', 'GalleryPublic::home');
        $this->router->allow('/about/', 'GalleryPublic::about');
        $this->router->allow('/category/', 'GalleryPublic::categories');
        $this->router->allow('/category/?/', 'GalleryPublic::category');
        $this->router->allow('/media/', 'GalleryPublic::medias');
        $this->router->allow('/media/?/', 'GalleryPublic::media');
        $this->router->allow('/page/', 'GalleryPublic::pages');
        $this->router->allow('/page/?/', 'GalleryPublic::page');
        // Admin Routes
        $this->router->allow('/admin/', 'GalleryAdmin::home');
        $this->router->allow('/admin/category/', 'GalleryAdmin::category');
        $this->router->allow('/admin/category/save/', 'GalleryAdmin::categorySave');
        $this->router->allow('/admin/media/', 'GalleryAdmin::media');
        $this->router->allow('/admin/media/save/', 'GalleryAdmin::mediaSave');
        $this->router->allow('/admin/page/', 'GalleryAdmin::page');
        $this->router->allow('/admin/page/save/', 'GalleryAdmin::pageSave');
        $this->router->allow('/admin/source/', 'GalleryAdmin::source');
    }
}
