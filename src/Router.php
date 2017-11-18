<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Attogram SharedMedia Gallery Router
 */
class Router extends Base
{
    const VERSION = '0.0.15';

    protected $uriBase;
    protected $uriRelative;
    protected $uri = [];
    protected $level;

    /**
     * @param int $level
     * @return bool
     */
    public function __construct(int $level = 0)
    {
		parent::__construct();
        $this->level = $level;
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
        return implode('/', $sUri) . '/';
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
        if (count($this->uri) <= 1) {
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
     * @return array
     */
    protected function getRoutes()
    {
        return [];
    }
}
