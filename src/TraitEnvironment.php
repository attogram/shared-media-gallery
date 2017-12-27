<?php

namespace Attogram\SharedMedia\Gallery;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

trait TraitEnvironment
{
    private $dotenv;

    /**
     * load the .env file
     * @return bool
     */
    private function setEnvironment()
    {
        if ($this->dotenv instanceof Dotenv) {
            return true;
        }
        try {
            $this->dotenv = new Dotenv(__DIR__ . '/../');
            $this->dotenv->load();
        } catch (InvalidPathException $error) {
            return false;
        }
        return true;
    }

    /**
     * Get list of Allowed IP Addresses
     * @return array
     */
    private function getAllowedIps()
    {
        if (!$this->setEnvironment()) {
            return [];
        }
        $envAllowedIps = getenv('ALLOWED_IPS');
        return explode(',', $envAllowedIps);
    }
}
