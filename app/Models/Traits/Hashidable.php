<?php

namespace App\Models\Traits;

use App\Services\EncrypterService;

trait Hashidable
{
    public function getRouteKey(): string
    {
        return app(EncrypterService::class)->encrypt($this->getKey());
    }
}
