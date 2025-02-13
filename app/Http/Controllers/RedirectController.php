<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\RedirectService;
use App\Services\VisitorService;
use Illuminate\Support\Facades\DB;

class RedirectController extends Controller
{
    /**
     * Redirect the client to the intended long URL (no checks are performed)
     * and executes the create visitor data task.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function __invoke(Url $url)
    {
        return DB::transaction(function () use ($url) {
            app(VisitorService::class)->create($url);

            return app(RedirectService::class)->execute($url);
        });
    }
}
