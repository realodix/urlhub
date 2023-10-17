<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\UrlRedirection;
use App\Services\VisitorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UrlRedirectController extends Controller
{
    /**
     * Redirect the client to the intended long URL (no checks are performed)
     * and executes the create visitor data task.
     *
     * @param string $urlKey A unique key to identify the shortened URL
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function __invoke(string $urlKey): RedirectResponse
    {
        return DB::transaction(function () use ($urlKey) {
            // firstOrFail() will throw a ModelNotFoundException if the URL is not
            // found and 404 will be returned to the client.
            $url = Url::whereKeyword($urlKey)->firstOrFail();

            app(VisitorService::class)->create($url);

            return app(UrlRedirection::class)->execute($url);
        });
    }
}
