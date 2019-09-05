<?php

namespace Mekaeil\LaravelUserManagement\Repository\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function allWithTrashed();
    
    public function restoreUser(int $ID);
}