<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Show all user short URLs.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $urlVisitCount = app(Visit::class)->currentUserLinkVisitCount();

        return view('backend.dashboard', [
            'url' => app(Url::class),
            'urlVisitCount' => n_abb($urlVisitCount),
        ]);
    }

    /**
     * Show shortened url details page.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Url $url)
    {
        Gate::authorize('updateUrl', $url);

        return view('backend.edit', ['url' => $url]);
    }

    /**
     * Update the destination URL.
     *
     * @param Request $request \Illuminate\Http\Request
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        $request->validate([
            'title'    => ['max:' . Url::TITLE_LENGTH],
            'long_url' => [
                'required', 'url', 'max:65535',
                new \App\Rules\NotBlacklistedDomain,
            ],
        ]);

        $url->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);

        return to_route('dashboard')
            ->with('flash_success', __('Link changed successfully !'));
    }

    /**
     * Delete shortened URLs.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        Gate::authorize('forceDelete', $url);

        $url->delete();

        return redirect()->back()
            ->with('flash_success', __('Link was successfully deleted.'));
    }
}
