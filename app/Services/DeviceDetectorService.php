<?php

namespace App\Services;

use DeviceDetector\DeviceDetector;

class DeviceDetectorService extends DeviceDetector
{
    public function __construct()
    {
        parent::__construct();

        // Remove unwanted parsers
        $this->deviceParsers = [];
        $this->botParsers = [];
    }

    /**
     * Returns the client data extracted from the parsed UA.
     *
     * @return string
     */
    public function getClientAttr(string $attr)
    {
        return $this->getClientAttribute($attr);
    }

    /**
     * Returns the operating system data extracted from the parsed UA
     *
     * @return string
     */
    public function getOsAttr(string $attr)
    {
        return $this->getOsAttribute($attr);
    }
}
