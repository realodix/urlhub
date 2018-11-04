<?php

namespace App\Http\Traits;

trait Hashidable
{
    public function getRouteKey()
    {
        return \Hashids::connection(get_called_class())->encode($this->getKey());
    }
}
