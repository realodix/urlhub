<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Url, User};
use App\Services\KeyGeneratorService;

class AboutSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.about', [
            'url'  => app(Url::class),
            'user' => app(User::class),
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }
}
