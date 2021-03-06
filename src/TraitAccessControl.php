<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Trait TraitAccessControl
 * @package Attogram\SharedMedia\Gallery
 */
trait TraitAccessControl
{
    /**
     * Allow access by IP address, or exit with error 403
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
}
