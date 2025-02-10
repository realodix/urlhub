<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;

class UserService
{
    /**
     * Generate unique identifiers for users, based on their IP address and more.
     *
     * If the user is logged in, the signature is simply the user's ID.
     */
    public function signature(): string
    {
        if (auth()->check() === false) {
            $device = Helper::deviceDetector();
            $browser = $device->getClient();
            $os = $device->getOs();

            $userDeviceInfo = implode([
                request()->ip(),
                $browser['name'] ?? '',
                $os['name'] ?? '',
                $os['version'] ?? '',
                $device->getDeviceName() . $device->getModel() . $device->getBrandName(),
                request()->getPreferredLanguage(),
            ]);

            return hash('xxh3', $userDeviceInfo);
        }

        return (string) auth()->id();
    }

    /**
     * Determine the type of user based on authentication status and device detection.
     */
    public function userType(): string
    {
        $type = UserType::User->value;
        $device = Helper::deviceDetector();

        if (auth()->check() === false) {
            $type = UserType::Guest->value;

            if ($device->isBot() === true) {
                $type = UserType::Bot->value;
            }
        }

        return $type;
    }
}
