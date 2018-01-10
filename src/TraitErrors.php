<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Trait TraitErrors
 * @package Attogram\SharedMedia\Gallery
 */
trait TraitErrors
{

    /**
     * Send 500 error with message, then exit
     * @param string $message
     */
    private function fatalError(string $message = '')
    {
        $this->shutdown('500 Internal Server Error', $message);
    }

    /**
     * Send 404 error with message, then exit
     * @param string $message
     */
    private function error404(string $message = '')
    {
        $this->shutdown('404 Page Not Found', $message);
    }

    /**
     * Send 403 Error with message, then exit
     * @param string $message
     */
    private function error403(string $message = '')
    {
        $this->shutdown('403 Forbidden', $message);
    }

    /**
     * Exit
     * @param string $status
     * @param string $message
     */
    private function shutdown(string $status, string $message = '')
    {
        header('HTTP/1.0 ' . $status);
        print '<html><head><title>' . $status . '</title></head>'
            . '<body><h1>' . $status . '</h1><p>' . $message . '</p></body></html>';
        exit;
    }
}
