<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\Helper;
use App\Models\Url;
use App\Models\Visit;
use App\Settings\GeneralSettings;
use Illuminate\Support\Uri;

class VisitService
{
    public function __construct(
        protected UserService $userService,
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
        $visit = new Visit;
        $logBotVisit = $this->settings->track_bot_visits;
        $referer = request()->header('referer');
        $botDetector = Helper::botDetector();
        $deviceDetector = Helper::deviceDetector();

        if ($logBotVisit === false && $botDetector->isCrawler()) {
            return;
        }

        $visit->url_id = $url->id;
        $visit->user_type = $this->userService->userType();
        $visit->user_uid = $this->userService->signature();
        $visit->is_first_click = $this->isFirstClick($url);
        $visit->referer = $this->getRefererHost($referer);
        $visit->browser = $deviceDetector->getClientAttr('name');
        $visit->os = $deviceDetector->getOsAttr('family');
        $visit->save();
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
            ->where('user_uid', $this->userService->signature())
            ->exists();

        return $hasVisited ? false : true;
    }

    /**
     * Get the referer host
     *
     * Only input the URL host into the referer column.
     */
    public function getRefererHost(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $referer = Uri::of($value);

        return $referer->scheme().'://'.$referer->host();
    }

    /**
     * The number of clicks from links created by the currently authenticated
     * user.
     */
    public function authUserLinkVisits(): int
    {
        return Visit::whereRelation('url', 'user_id', auth()->id())
            ->count();
    }

    /**
     * The number of clicks from links created by all registered users.
     */
    public function userLinkVisits(): int
    {
        return Visit::whereRelation('url', 'user_type', UserType::User)
            ->count();
    }

    /**
     * The number of clicks from links created by all guest users.
     */
    public function guestLinkVisits(): int
    {
        return Visit::whereRelation('url', 'user_type', UserType::Guest)
            ->count();
    }

    /**
     *  Total users who clicked on a link.
     */
    public function userVisits(): int
    {
        return Visit::where('user_type', UserType::User)->count();
    }

    /**
     * Total guest users who clicked on a link.
     *
     * @param bool $unique Whether to count unique guest users or all guest visits.
     * @return int
     */
    public function guestVisits(bool $unique = false)
    {
        return Visit::isGuest()
            ->when($unique, fn($query) => $query->distinct('user_uid'))
            ->count();
    }

    /**
     * Total unique guest users who clicked on a link.
     */
    public function uniqueGuestVisits(): int
    {
        return $this->guestVisits(true);
    }
}
