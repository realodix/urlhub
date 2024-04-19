<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Services\KeyGeneratorService;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class AboutSystemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.about', [
            'url'  => app(Url::class),
            'user' => app(User::class),
            'visit' => app(Visit::class),
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }
}
