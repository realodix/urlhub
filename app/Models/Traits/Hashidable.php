<?php

namespace App\Models\Traits;

trait Hashidable
{
    public function getRouteKey(): string
    {
        return encrypt($this->getKey());
    }
}
