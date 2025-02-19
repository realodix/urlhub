<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Cache;

class UserService
{
    /**
     * Generate unique identifiers for users, based on their IP address and more.
     * If the user is logged in, the signature is simply the user's ID.
     */
    public function signature(): string
    {
        if (auth()->check()) {
            return (string) auth()->id();
        }

        // 1. Cache the device info hash.
        $cacheKey = 'device_signature_'.md5(request()->userAgent().request()->ip());
        $cachedSignature = Cache::get($cacheKey);
        if ($cachedSignature) {
            return $cachedSignature;
        }

        $device = Helper::deviceDetector();
        $browser = $device->getClient();
        $os = $device->getOs();
        $deviceInfo = implode([
            request()->ip(),
            $browser['name'] ?? '',
            $os['name'] ?? '',
            $os['version'] ?? '',
            $device->getDeviceName().$device->getModel().$device->getBrandName(),
            request()->getPreferredLanguage(),
        ]);

        $signature = hash('xxh3', $deviceInfo);

        // 2. Store in cache for a reasonable duration
        Cache::put($cacheKey, $signature, now()->addHour());

        return $signature;
    }

    /**
     * Determine the type of user based on authentication status and device
     * detection.
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
