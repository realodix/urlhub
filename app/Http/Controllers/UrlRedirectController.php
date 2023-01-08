<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Url;
use App\Services\UrlRedirection;
use App\Services\VisitorService;
use Illuminate\Support\Facades\DB;

class UrlRedirectController extends Controller
{
    public function __construct(
        public VisitorService $visitorService,
    ) {
    }

    /**
     * Redirect the client to the intended long URL (no checks are performed)
     * and executes the create visitor data task.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(UrlRedirection $urlRedirection, string $key)
    {
        return DB::transaction(function () use ($urlRedirection, $key) {
            $url = Url::whereKeyword($key)->firstOrFail();

            $data = [
                'url_id'          => $url->id,
                'is_first_click'  => $this->visitorService->isFirstClick($url),
                'referer'         => request()->header('referer'),
                'ip'              => Helper::anonymizeIp(request()->ip()),
                'browser'         => \Browser::browserFamily(),
                'browser_version' => \Browser::browserVersion(),
                'device'          => \Browser::deviceType(),
                'os'              => \Browser::platformFamily(),
                'os_version'      => \Browser::platformVersion(),
            ];

            $this->visitorService->storeVisitorData($data);

            return $urlRedirection->execute($url);
        });
    }
}
