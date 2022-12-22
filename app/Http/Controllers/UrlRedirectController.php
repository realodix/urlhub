<?php

namespace App\Http\Controllers;

use App\Actions\UrlRedirectionAction;
use App\Models\Url;
use Illuminate\Support\Facades\DB;

class UrlRedirectController extends Controller
{
    /**
     * Handle the logging of the URL and redirect the user to the intended
     * long URL.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(UrlRedirectionAction $action, string $key)
    {
        return DB::transaction(function () use ($action, $key) {
            $url = Url::whereKeyword($key)->firstOrFail();

            return $action->handleHttpRedirect($url);
        });
    }
}
