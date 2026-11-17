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
}
