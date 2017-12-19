<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\Tools;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

/**
 * Attogram SharedMedia Gallery Base
 */
class Base extends Router
{
    const VERSION = '0.0.4';

    const ITEMS_PER_PAGE = 10;

    protected $twig;
    protected $data = [];
    protected $galleryTools;

    /**
     * @param int $level
     */
    public function __construct(int $level = 0)
    {
        $this->setupTemplating();
        $this->setupDatabase();
        $this->galleryTools = new GalleryTools;
        $this->data = $this->galleryTools->setup($this->data);
        $this->data['title'] = 'Shared Media Gallery';

        parent::__construct($level); // Route
    }

    private function setupTemplating()
    {
        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem(dirname(__FILE__).'/../views/'),
            [
                //'cache' => dirname(__FILE__).'/../cache/',
                //'auto_reload' => true,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new Twig_Extension_Debug());
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

    /**
     * @param string $message
     * @return void
     */
    protected function error404(string $message = '')
    {
        header('HTTP/1.0 404 Not Found');
        if (!$message) {
            $message = '404 Not Found';
        }
        $this->data['message'] = $message;
        $this->displayView('error');
    }

    /**
     * @param string $view
     * @return bool
     */
    protected function displayView(string $view)
    {
        $this->data['uriBase'] = $this->uriBase;
        $this->data['uriRelative'] = $this->uriRelative;
        $this->data['uri'] = $this->uri;
        $this->data['routes'] = $this->getRoutes();
        $this->data['level'] = $this->level;
        if (!$this->callControl($view)) {
            return false;
        }
        try {
            $this->twig->display($view.'.twig', $this->data);
        } catch (Twig_Error_Loader $error) {
            print 'ERROR: '.$error->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @param string $view
     * @return bool
     */
    protected function callControl(string $view)
    {
        $view = ucfirst(strtolower($view));
        if (strpos($view, '/')) {
            $fullView = '';
            foreach (explode('/', $view) as $name) {
                $name = str_replace(['.', ' '], '', $name);
                $fullView .= ucfirst(strtolower($name));
            }
            $view = $fullView;
        }
        $control = 'control'.$view;
        if (is_callable([$this, $control]) && !$this->{$control}()) {
            return false;
        }
        return true;
    }
}
