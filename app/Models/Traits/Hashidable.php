<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Crypt;

trait Hashidable
{
    public function getRouteKey(): string
    {
        return Crypt::encryptString($this->getKey());
    }
}
