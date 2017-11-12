<?php

namespace Attogram\SharedMedia\Gallery;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Base
{
    const VERSION = '0.0.2';

    protected $twig;
    protected $uriBase;
    protected $uriRelative;

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
        if (preg_match('#/$#', $this->uriRelative)) { // If has slash at end
            return true;
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.rtrim($this->uriBase, '/').$this->uriRelative.'/');  // Force trailing slash
        return false;
    }

    private function getServer($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }

    protected function getDefaultData()
    {
        return [
            'title' => 'Shared Media Gallery',
            'version' => self::VERSION,
            'uriBase' => $this->uriBase,
            'uriRelative' => $this->uriRelative,
        ];
    }

    protected function route()
    {
    }
}
