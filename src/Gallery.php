<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\Router\Router;

class Gallery
{
    use TraitDatabase;
    use TraitView;

    const VERSION = '0.1.2';

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
            $this->error404('404 Page Not Found');
            return;
        }
        $this->callControl($control);
    }

    /**
     * @param string $control
     * @return void
     */
    private function callControl($control)
    {
        list($className, $methodName) = explode('::', $control);
        $className = 'Attogram\\SharedMedia\\Gallery\\' . $className;
        if (!is_callable([$className, $methodName])) {
            $this->error404('404 Control Not Found');
            return;
        }
        $this->setupDatabase();
        $galleryTools = new GalleryTools;
        $this->data['media_count'] = $galleryTools->getMediaCount();
        $this->data['category_count'] = $galleryTools->getCategoryCount();
        $this->data['page_count'] = $galleryTools->getPageCount();
        $this->data['source_count'] = $galleryTools->getSourceCount();
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
