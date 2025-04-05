<?php

namespace App\Actions;

use App\Helpers\Helper;
use App\Models\Url;
use App\Models\Visit;
use App\Services\UserService;
use App\Services\VisitService;
use App\Settings\GeneralSettings;

class RecordVisit
{
    public function __construct(
        protected UserService $userService,
        protected VisitService $visitService,
    ) {}

    /**
     * @param Url $url \App\Models\Url
     * @return void
     */
    public function handle(Url $url)
    {
        $visit = new Visit;
        $botDetector = Helper::botDetector();
        $deviceDetector = Helper::deviceDetector();
        $referer = request()->header('referer');

        if ($botDetector->isCrawler()
            && app(GeneralSettings::class)->track_bot_visits === false
        ) {
            return;
        }

        $visit->url_id = $url->id;
        $visit->user_type = $this->userService->userType();
        $visit->user_uid = $this->userService->signature();
        $visit->is_first_click = $visit->isFirstClick($url);
        $visit->referer = $this->visitService->getRefererHost($referer);
        $visit->browser = $deviceDetector->getClientAttr('name');
        $visit->os = $deviceDetector->getOsAttr('family');
        $visit->save();
    }
}
