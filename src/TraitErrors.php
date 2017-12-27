<?php

namespace Attogram\SharedMedia\Gallery;

trait TraitErrors
{
    /**
     * Send 404 error with message, then exit
     * @param string $message
     */
    protected function error404(string $message = '404 Page Not Found')
    {
        $this->shutdown('404 Page Not Found', $message);
    }

    /**
     * Send 403 Error with message, then exit
     * @param string $message
     */
    private function error403(string $message = '403 Forbidden')
    {
        $this->shutdown('403 Forbidden', $message);
    }

    /**
     * Exit
     * @param string $status
     * @param string $message
     */
    private function shutdown(string $status, string $message)
    {
        header('HTTP/1.0 ' . $status);
        print '<html><head><title>' . $message . '</title></head>'
            . '<body><h1>' . $message . '</h1></body></html>';
        exit;
    }
}
