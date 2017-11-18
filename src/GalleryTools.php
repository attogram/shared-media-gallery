<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Exception\RuntimeException;

class GalleryTools
{
    const VERSION = '0.0.1';

    public function getCategoryCount()
    {
        try {
            return CategoryQuery::create()->count();
        } catch(RuntimeException $error) {
            print '<pre>ERROR: getCategoryCount: ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }

    public function getMediaCount()
    {
        try {
            return MediaQuery::create()->count();
        } catch(RuntimeException $error) {
            print '<pre>ERROR: getMediaCount: ' . $error->getMessage() . '</pre>';
            return 0;
        }
    }


    public function setupDatabase()
    {
        $dsn = 'sqlite:' . __DIR__ . '/../database/gallery.sq3';
        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'sqlite');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration(array (
          'dsn' => $dsn,
          'user' => 'root',
          'password' => '',
          'settings' =>
          array (
            'charset' => 'utf8',
            'queries' =>
            array (
            ),
          ),
          'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
          'model_paths' =>
          array (
            0 => 'vendor/attogram/shared-media-orm/src/Attogram/SharedMedia/Orm/',
          ),
        ));
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');
    }
}
