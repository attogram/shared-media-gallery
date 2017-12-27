<?php

namespace Attogram\SharedMedia\Gallery;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

trait TraitAccessControl
{
    /**
     * Allow access by IP address, or exit with error 403
     * @return void
     */
    private function accessControl()
    {
        $allowedIps = $this->getAllowedIps();
        $remoteIp = $this->getRemoteIp();
        if (!in_array($remoteIp, $allowedIps)) {
            $this->error403('Access Denied');
        }
    }

    /**
     * Get list of Allowed IP Addresses from the .env file, or exit with error 403
     * @return array
     */
    private function getAllowedIps()
    {
        try {
            $dotenv = new Dotenv(__DIR__ . '/../');
            $dotenv->load();
        } catch (InvalidPathException $error) {
            $this->error403('Env Not Found');
        }
        $envAllowedIps = getenv('ALLOWED_IPS');
        if (!$envAllowedIps) {
            $this->error403('Allow List Not Found');
        }
        return explode(',', $envAllowedIps);
    }

    /**
     * Get Remote IP Address, or exit with error 403
     * @return string
     */
    private function getRemoteIp()
    {
        $remoteIp = $this->getServer('REMOTE_ADDR');
        if (!$remoteIp) {
            $this->error403('Remote IP Not Found');
        }
        return $remoteIp;
    }
    /**
     * Send 403 Error with message, then exit
     * @param string $msg
     */
    private function error403($msg = 'Forbidden')
    {
        header('HTTP/1.0 403 Forbidden');
        print '403 ' . $msg;
        exit;
    }
}
