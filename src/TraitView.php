<?php

namespace Attogram\SharedMedia\Gallery;

use Throwable;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Syntax;
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
     * @return void
     */
    private function displayView(string $view)
    {
        if (!$this->twig) {
            $this->setupTwig();
        }
        try {
            $this->twig->display($view.'.twig', $this->data);
        } catch (Twig_Error_Loader | Twig_Error_Syntax | Throwable $error) {
            print 'Error: ' . get_class($error) . ': ' . $view;
        }
    }
}
