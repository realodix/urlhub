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
     * @param Url $url \App\Models\Url
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function __invoke(Url $url): RedirectResponse
    {
        return DB::transaction(function () use ($url) {
            app(VisitorService::class)->create($url);

            return app(UrlRedirection::class)->execute($url);
        });
    }
}
