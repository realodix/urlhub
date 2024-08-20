<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class AboutSystemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.about', [
            'url'   => app(\App\Models\Url::class),
            'user'  => app(\App\Models\User::class),
            'visit' => app(\App\Models\Visit::class),
            'keyGenerator' => app(\App\Services\KeyGeneratorService::class),
        ]);
    }
}
