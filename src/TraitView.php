<?php

namespace Attogram\SharedMedia\Gallery;

use Throwable;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

/**
 * Trait TraitView
 * @package Attogram\SharedMedia\Gallery
 */
trait TraitView
{
    /** @var Twig_Environment $twig */
    private $twig;

    private function setupTwig()
    {
        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem(__DIR__ . '/../views/'),
            [
                //'cache' => __DIR__ . '/../cache/',
                //'auto_reload' => true,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new Twig_Extension_Debug());
    }

    /**
     * @param string $view
     */
    private function displayView(string $view)
    {
        if (!$this->twig instanceof Twig_Environment) {
            $this->setupTwig();
        }
        try {
            $this->twig->display($view.'.twig', $this->data);
        } catch (Throwable $error) {
            print 'Error: ' . get_class($error) . ': ' . $view;
        }
    }
}
