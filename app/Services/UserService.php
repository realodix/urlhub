<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;
use App\Models\Url;

class UserService
{
    /*
     * The number of guest users.
     */
    public function guestUsers(): int
    {
        return Url::where('user_type', UserType::Guest)
            ->distinct('user_uid')
            ->count();
    }

    /**
     * Generate unique identifiers for users, based on their IP address and more.
     * If the user is logged in, the signature is simply the user's ID.
     */
    public function signature(): string
    {
        if (auth()->check()) {
            return (string) auth()->id();
        }

        $device = Helper::deviceDetector();
        $deviceInfo = implode([
            request()->ip(),
            $device->getClientAttr('name'),
            $device->getOsAttr('name'),
            $device->getOsAttr('version'),
            request()->getPreferredLanguage(),
        ]);

        return hash('xxh3', $deviceInfo);
    }

    /**
     * Determine the type of user based on authentication status and device
     * detection.
     *
     * @return \App\Enums\UserType
     */
    public function userType()
    {
        $type = UserType::User;

        if (auth()->check() === false) {
            $type = UserType::Guest;

            $botDetector = Helper::botDetector();
            if ($botDetector->isCrawler()) {
                $type = UserType::Bot;
            }
        }

        return $type;
    }
}
