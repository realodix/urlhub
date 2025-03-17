<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
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

        return redirect()->route('link.edit', $url)->with('flash_success', __('Password has been set!'));
    }

    /**
     * Show the form for editing the specified password.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Url $url)
    {
        return view('backend.linkpassword.edit', ['url' => $url]);
    }

    /**
     * Update the specified password in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Url $url)
    {
        $request->validate([
            'password' => ['required', Password::min(Url::PWD_MIN_LENGTH), 'confirmed'],
        ]);

        $url->update(['password' => $request->password]);

        return redirect()
            ->route('link.edit', $url)
            ->with('flash_success', __('Password has been updated!'));
    }

    /**
     * Remove the specified password from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Url $url)
    {
        $url->update(['password' => null]);

        return redirect()
            ->route('link.edit', $url)
            ->with('flash_success', __('Password has been removed!'));
    }
}
