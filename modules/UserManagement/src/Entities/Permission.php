<?php

namespace Mekaeil\LaravelUserManagement\Entities;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    
}
