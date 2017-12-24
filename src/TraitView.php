<?php

namespace Attogram\SharedMedia\Gallery;

use Twig_Environment;
use Twig_Error_Loader;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

trait TraitView
{
    private $twig = false;

    private function setupTwig()
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

    /**
     * @param string $view
     * @param array  $array
     * @return void
     */
    private function displayView(string $view, array $data = [])
    {
        if (!$this->twig) {
            $this->setupTwig();
        }
        try {
            $this->twig->display($view.'.twig', $data);
        } catch (Twig_Error_Loader $error) {
            print 'ERROR: ' . $error->getMessage();
        }
    }
}
