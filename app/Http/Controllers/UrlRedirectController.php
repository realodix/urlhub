<?php

namespace App\Http\Controllers;

use App\Custom\Services\UrlRedirectionService;
use App\Url;
use Illuminate\Http\RedirectResponse;
use DB;

class UrlRedirectController extends Controller
{
    /**
     * Handle the logging of the URL and redirect the
     * user to the intended long URL.
     *
     * @param UrlRedirectionService $service
     * @param string $url_key
     * @return RedirectResponse
     */
    public function __invoke(UrlRedirectionService $service, string $url_key)
    {
        return DB::transaction(function () use ($service, $url_key) {
            $url = Url::whereUrlKey($url_key)->firstOrFail();

            return $service->handleHttpRedirect($url);
        });
    }

}
