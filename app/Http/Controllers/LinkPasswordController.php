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
     */
    public function create(Url $url)
    {
        Gate::authorize('view', $url);

        return view('backend.linkpassword.create', ['url' => $url]);
    }

    /**
     * Store a newly created password in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Url $url)
    {
        $request->validate([
            'password' => ['required', Password::min(Url::PWD_MIN_LENGTH), 'confirmed'],
        ]);

        $url->update(['password' => $request->password]);

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been set!');
    }

    /**
     * Show the form for editing the specified password.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Url $url)
    {
        Gate::authorize('view', $url);

        return view('backend.linkpassword.edit', ['url' => $url]);
    }

    /**
     * Update the password from the specified link.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Url $url)
    {
        $request->validate([
            'password' => ['required', Password::min(Url::PWD_MIN_LENGTH), 'confirmed'],
        ]);

        $url->update(['password' => $request->password]);

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been updated!');
    }

    /**
     * Remove the password from the specified link.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Url $url)
    {
        $url->update(['password' => null]);

        return to_route('link.edit', $url)
            ->with('flash_success', 'Password has been removed!');
    }
}
