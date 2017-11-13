<?php

namespace Attogram\SharedMedia\Gallery;

use Twig_Environment;
use Twig_Error_Loader;
use Twig_Loader_Filesystem;

class Base
{
    const VERSION = '0.0.4';

    protected $twig;
    protected $uriBase;
    protected $uriRelative;
    protected $uri = [];

    public function __construct()
    {
        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem('../views/'),
            [
                'cache' => '../cache/',
                'auto_reload' => true,
            ]
        );

        if (!$this->setUri()) {
            return false;
        }
        $this->route();
    }

    private function setUri()
    {
        $this->uriBase = strtr($this->getServer('SCRIPT_NAME'), ['index.php' => '']);

        $this->uriRelative = strtr($this->getServer('REQUEST_URI'), [$this->uriBase => '/']);

        $this->uriBase = rtrim($this->uriBase, '/');

        $this->setUriList();

        // If has slash at end, all is OK
        if (preg_match('#/$#', $this->uriRelative)) {
            return true;
        }
        // Force trailing slash
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.$this->uriBase.$this->uriRelative.'/');
        return false;
    }

    private function setUriList()
    {
        $this->uri = explode('/', $this->uriRelative); // make uri list
        if ($this->uri[0] === '') { // trim off first empty element
            unset($this->uri[0]);
            $this->uri = array_values($this->uri); // reindex
        }
        if (count($this->uri) == 1) {
            return;
        }
        if ($this->uri[count($this->uri)-1] === '') { // trim off last empty element
            unset($this->uri[count($this->uri)-1]);
            $this->uri = array_values($this->uri); // reindex
        }
    }

    private function getServer($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }

    private function route()
    {
        if (!in_array($this->uri[0], array_column($this->getRoutes(), '0'))) {
            $this->error404();
            return false;
        }

        if ($this->routeLevel0()) {
            return true;
        }

        if ($this->routeLevel1()) {
            return true;
        }

        $this->error404();
        return false;
    }

    private function routeLevel0()
    {
        if (isset($this->uri[1])) {
            return false;
        }
        foreach ($this->getRoutes() as $view => $route) {
            if ($route[0] === $this->uri[0] && !isset($route[1])) {
                $this->displayView($view);
                return true;
            }
        }
        return false;
    }

    private function routeLevel1()
    {
        if (isset($this->uri[2])) {
            return false;
        }
        foreach ($this->getRoutes() as $view => $route) {
            if ($route[0] != $this->uri[0] || !isset($route[1])) {
                continue;
            }
            if ($route[1] === '*' || $route[1] === $this->uri[1]) {
                $this->displayView($view);
                return true;
            }
        }
        return false;
    }

    private function error404()
    {
        header('HTTP/1.0 404 Not Found');
        $this->displayView('error');
    }

    private function displayView($view)
    {
        try {
            $this->twig->display($view.'.twig', $this->getViewData());
        } catch (Twig_Error_Loader $error) {
            print 'ERROR: '.$error->getMessage();
        }
    }

    protected function getRoutes()
    {
        return [];
    }

    protected function getViewData()
    {
        return [];
    }
}
