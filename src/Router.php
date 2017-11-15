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
    const VERSION = '0.0.11';

    protected $twig;
    protected $uriBase;
    protected $uriRelative;
    protected $uri = [];
    protected $level;
    protected $data = [];

    /**
     * @param int $level
     * @return bool
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
        return $this->route();
    }

    /**
     * @return bool
     */
    private function setUri()
    {
        $this->uriBase = strtr(Tools::getServer('SCRIPT_NAME'), ['index.php' => '']);
        $rUri = preg_replace('/\?.*/', '', Tools::getServer('REQUEST_URI')); // remove query
        $this->uriRelative = strtr($rUri, [$this->uriBase => '/']);
        $this->uriBase = $this->trimUriLevel($this->uriBase);
        $this->uriBase = rtrim($this->uriBase, '/'); // remove trailing slash from base URI
        $this->setUriList(); // set the uri Array
        if (preg_match('#/$#', $this->uriRelative)) {
            return true; // If relative URI has slash at end, all is OK
        }
        header('HTTP/1.1 301 Moved Permanently'); // Force trailing slash
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
     * @return bool
     */
    private function displayView(string $view)
    {
        $this->data['version'] = self::VERSION;
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
    private function callControl(string $view)
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
    protected function getRoutes()
    {
        return [];
    }

    /**
     * @param int    $number
     * @param string $message
     * @param string $file
     * @param int    $line
     * @return bool
     */
    protected function errorHandler(int $number, string $message, string $file = null, int $line = null)
    {
        print "<pre>ERROR: $number: $message - $file : $line</pre>";
        return true; // true = do not use normal error handler.  false = continue with normal error handler
    }
}
