<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class LinkPasswordController extends Controller
{
    /**
     * Show the form for creating a new password.
     *
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

        return view('backend.linkpassword.create', ['url' => $url]);
    }

    /**
     * Store a newly created password in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

        $request->validate([
            'password' => ['required', Password::min(Url::PWD_MIN_LENGTH), 'confirmed'],
        ]);

        $url->password = $request->password;
        $url->save();

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been set!');
    }

    /**
     * Show the form for editing the specified password.
     *
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

        return view('backend.linkpassword.edit', ['url' => $url]);
    }

    /**
     * Update the password from the specified link.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

        $request->validate([
            'password' => ['required', Password::min(Url::PWD_MIN_LENGTH), 'confirmed'],
        ]);

        $url->password = $request->password;
        $url->save();

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been updated!');
    }

    /**
     * Remove the password from the specified link.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

        $url->password = null;
        $url->save();

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been removed!');
    }
}
