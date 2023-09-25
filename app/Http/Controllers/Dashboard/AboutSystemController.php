<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\KeyGeneratorService;

class AboutSystemController extends Controller
{
    /**
     * AllUrlController constructor.
     */
    public function __construct(
        public User $user,
    ) {
        $this->middleware('role:admin');
    }

    /**
     * Show all short URLs created by all users.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.about', [
            'user' => $this->user,
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }
}
