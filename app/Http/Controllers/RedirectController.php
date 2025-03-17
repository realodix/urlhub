<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\RedirectService;
use App\Services\VisitorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RedirectController extends Controller
{
    /**
     * Redirect the client to the intended long URL (no checks are performed)
     * and executes the create visitor data task.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Url $url)
    {
        if ($url->password) {
            return redirect(route('link.password', $url->keyword));
        }

        return $this->handleRedirect($url);
    }

    /**
     * Displays the password form for a link.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View
     */
    public function password(Url $url)
    {
        return view('frontend.linkpassword', ['url' => $url]);
    }

    /**
     * Validate the given password against the stored one for the given URL.
     * If it matches, redirect the user to the long URL.
     * If it doesn't, redirect the user back with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(Request $request, Url $url)
    {
        if (Hash::check($request->password, $url->password)) {
            return $this->handleRedirect($url);
        }

        return back()->withErrors(['password' => 'The password is incorrect.']);
    }

    /**
     * Handles the redirect logic and visitor creation.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function handleRedirect(Url $url)
    {
        return DB::transaction(function () use ($url) {
            app(VisitorService::class)->create($url);

            return app(RedirectService::class)->execute($url);
        });
    }
}
