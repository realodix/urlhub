<?php

namespace App\Models\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    public function getRouteKey()
    {
        /** @var \Vinkla\Hashids\Facades\Hashids */
        $hashids = Hashids::connection(get_called_class());

        return $hashids->encode($this->getKey());
    }
}
