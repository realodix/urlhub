<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;
use App\Models\Url;

class UserService
{
    /*
     * Counts the total number of unique guest users.
     *
     * This count is based on distinct `user_uid` values associated with links
     * created by users identified as `UserType::Guest`.
     */
    public function guestUsers(): int
    {
        return Url::where('user_type', UserType::Guest)
            ->distinct('user_uid')
            ->count();
    }

    /**
     * Generates a unique signature for the current user.
     *
     * If the user is authenticated, their user ID is returned. For guest users,
     * a unique hash is generated. This signature serves as a unique identifier
     * for guest activities.
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
     * Determines the type of the current user.
     *
     * The user type is primarily based on authentication status (logged-in vs. guest).
     * Additionally, for unauthenticated users, it checks if the client is a bot.
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
