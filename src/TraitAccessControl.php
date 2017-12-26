<?php

namespace Attogram\SharedMedia\Gallery;

use Dotenv\Dotenv;

trait TraitAccessControl
{
    /**
     * Allow access by IP address, or exit with error 403;
     * @return void
     */
    private function accessControl()
    {
        $allowedIps = $this->getAllowedIps();
        if (!$allowedIps) {
            $this->error403('Access Control Error');
        }
        $ip = $this->getServer('REMOTE_ADDR');
        if (!in_array($ip, $allowedIps)) {
            $this->error403('Access Denied');
        }
    }

    /**
     * @return array|void
     */
    private function getAllowedIps()
    {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();
        $envAllowedIps = getenv('ALLOWED_IPS');
        if ($envAllowedIps) {
            return explode(',', $envAllowedIps);
        }
    }

    /**
     * @param string $msg
     */
    private function error403($msg = 'Forbidden')
    {
        header('HTTP/1.0 403 Forbidden');
        print '403 ' . $msg;
        exit;
    }

}
