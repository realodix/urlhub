<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateUserEmail($data, $user)
    {
        $user->email = $data['email'];
        $user->save();
    }

    public function updateUserPassword($data, $user)
    {
        $user->password = Hash::make($data['new-password']);
        $user->save();
    }
}
