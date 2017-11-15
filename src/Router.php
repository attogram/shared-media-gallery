<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\Tools;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Loader_Filesystem;

/**
 * Attogram SharedMedia Gallery Router
 */
class Router
{
    const VERSION = '0.0.8';

    protected $twig;
    protected $uriBase;
    protected $uriRelative;
    protected $uri = [];
    protected $level;

    /**
     * @param int $level
     * @return void
     */
    public function __construct(int $level = 0)
    {
        set_error_handler([$this, 'errorHandler']);
        $this->level = $level;
        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem(dirname(__FILE__).'/../views/'),
            [
                'cache' => dirname(__FILE__).'/../cache/',
                'auto_reload' => true,
            ]
        );
        if (!$this->setUri()) {
            return false;
        }
        $this->route();
    }

    protected function errorHandler($number, $message, $file, $line)
    {
        print "<pre>ERROR: $number: $message - $file : $line</pre>";
    }

    /**
     * @return int
     */
    private function setUri()
    {
        $this->uriBase = strtr(Tools::getServer('SCRIPT_NAME'), ['index.php' => '']);
        $rUri = preg_replace('/\?.*/', '', Tools::getServer('REQUEST_URI')); // remove query
        $this->uriRelative = strtr($rUri, [$this->uriBase => '/']);
        $this->uriBase = $this->trimUriLevel($this->uriBase);
        $this->uriBase = rtrim($this->uriBase, '/'); // remove trailing slash from base URI
        $this->setUriList();
        // If relative URI has slash at end, all is OK
        if (preg_match('#/$#', $this->uriRelative)) {
            return true;
        }
        // Force trailing slash
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.$this->uriBase.$this->uriRelative.'/');
        return false;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function trimUriLevel(string $uri)
    {
        if (!$this->level) {
            return $uri;
        }
        $sUri = explode('/', $uri);
        array_pop($sUri);
        for ($i = 0; $i < $this->level; $i++) {
            array_pop($sUri);
        }
        return implode('/', $sUri);
    }

    /**
     * @return void
     */
    private function setUriList()
    {
        $this->uri = explode('/', $this->uriRelative); // make uri list
        if ($this->uri[0] === '') { // trim off first empty element
            array_shift($this->uri);
        }
        if (count($this->uri) == 1) {
            return;
        }
        if ($this->uri[count($this->uri)-1] === '') { // trim off last empty element
            array_pop($this->uri);
        }
    }

    /**
     * @return bool
     */
    private function route()
    {
        if (!in_array($this->uri[0], array_column($this->getRoutes(), '0'))) {
            $this->error404('404 Page Not Found');
            return false;
        }

        if ($this->routeLevel0()) {
            return true;
        }

        if ($this->routeLevel1()) {
            return true;
        }

        $this->error404('414 URI Too Long ');
        return false;
    }

    /**
     * @return bool
     */
    private function routeLevel0()
    {
        if (isset($this->uri[1])) {
            return false;
        }
        foreach ($this->getRoutes() as $view => $route) {
            if ($route[0] === $this->uri[0]
                && !isset($route[1])
            ) {
                $this->displayView($view);
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function routeLevel1()
    {
        if (isset($this->uri[2])) {
            return false;
        }
        foreach ($this->getRoutes() as $view => $route) {
            if ($route[0] === $this->uri[0]
                && isset($route[1])
                && in_array($route[1], ['*', $this->uri[1]])
                && !isset($route[2])
            ) {
                $this->displayView($view);
                return true;
            }
        }
        return false;
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
        $this->displayView('error', ['message' => $message]);
    }

    /**
     * @param string $view
     * @param array|null $data
     * @return bool
     */
    private function displayView($view, $data = [])
    {
        if (!$this->callControl($view)) {
            return false;
        }

        $data = array_merge($data, $this->getViewData());
        try {
            $this->twig->display($view.'.twig', $data);
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
    private function callControl($view)
    {
        $view = ucfirst(strtolower($view));
        if (strpos($view, '/')) {
            $fullView = '';
            foreach (explode('/', $view) as $name) {
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

    /**
     * @return array
     */
    protected function getViewData()
    {
        return [
            'title'       => 'Shared Media Gallery',
            'version'     => self::VERSION,
            'uriBase'     => $this->uriBase,
            'uriRelative' => $this->uriRelative,
            'uri'         => $this->uri,
            'routes'      => $this->getRoutes(),
        ];
    }

    /**
     * @return array
     */
    protected function getRoutes()
    {
        return [];
    }
}
