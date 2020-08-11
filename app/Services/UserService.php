<?php

namespace App\Services;

use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateUserEmail($data, $user)
    {
        $user->email = $data['email'];
        $user->save();

        return $user;
    }

    public function updateUserPassword($data, $user)
    {
        $user->password = Hash::make($data['new-password']);
        $user->save();

        return $user;
    }

    public function userCount()
    {
        return User::count();
    }

    /*
     * Count the number of guests in the url column based on IP and grouped
     * by ip.
     */
    public function guestCount()
    {
        return Url::select('ip', DB::raw('count(*) as total'))
        ->whereNull('user_id')
        ->groupBy('ip')
            ->get()
            ->count();
    }
}
