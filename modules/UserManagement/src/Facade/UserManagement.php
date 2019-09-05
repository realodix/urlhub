<?php

namespace Mekaeil\LaravelUserManagement\Facade;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class UserManagement extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'UserManagement';
    }
}
