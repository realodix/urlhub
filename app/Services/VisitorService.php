<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Settings\GeneralSettings;
use Illuminate\Support\Uri;

class VisitorService
{
    public function __construct(
        protected User $user,
        protected GeneralSettings $settings,
    ) {}

    /**
     * Store the visitor data.
     *
     * @param Url $url \App\Models\Url
     * @return void
     */
    public function create(Url $url)
    {
        $logBotVisit = $this->settings->track_bot_visits;
        $device = Helper::deviceDetector();
        $referer = request()->header('referer');

        if ($logBotVisit === false && $device->isBot() === true) {
            return;
        }

        Visit::create([
            'url_id'         => $url->id,
            'user_type'      => $this->userType(),
            'user_uid'       => $this->user->signature(),
            'is_first_click' => $this->isFirstClick($url),
            'referer'        => $this->getRefererHost($referer),
        ]);
    }

    /**
     * Determine the type of user based on authentication status and device detection.
     *
     * @return string The user type, which can be 'user', 'guest', or 'bot'.
     */
    public function userType(): string
    {
        $type = UserType::User->value;
        $device = Helper::deviceDetector();

        if (auth()->check() === false) {
            $type = UserType::Guest->value;
        }

        if ($device->isBot() === true) {
            $type = UserType::Bot->value;
        }

        return $type;
    }

    /**
     * Check if the visitor has clicked the link before. If the visitor has not
     * clicked the link before, return true.
     *
     * @param Url $url \App\Models\Url
     */
    public function isFirstClick(Url $url): bool
    {
        $hasVisited = $url->visits()
            ->where('user_uid', $this->user->signature())
            ->exists();

        return $hasVisited ? false : true;
    }

    /**
     * Get the referer host.
     *
     * Only input the URL host into the referer column
     */
    public function getRefererHost(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $referer = Uri::of($value);

        return $referer->scheme() . '://' . $referer->host();
    }
}
