<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyGeneratorService;

class AboutSystemController extends Controller
{
    public function __construct(
        public Url $url,
        public User $user,
    ) {
        $this->middleware('role:admin');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.about', [
            'url'  => $this->url,
            'user' => $this->user,
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }
}
