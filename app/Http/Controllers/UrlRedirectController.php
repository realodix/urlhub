<?php

namespace App\Http\Controllers;

use App\Services\UrlRedirectionService;
use App\Url;
use Illuminate\Support\Facades\DB;

class UrlRedirectController extends Controller
{
    /**
     * Handle the logging of the URL and redirect the user to the intended
     * long URL.
     *
     * @param UrlRedirectionService $service
     * @param string                $keyword
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(UrlRedirectionService $service, string $keyword)
    {
        return DB::transaction(function () use ($service, $keyword) {
            $url = Url::whereKeyword($keyword)->firstOrFail();

            return $service->handleHttpRedirect($url);
        });
    }
}
