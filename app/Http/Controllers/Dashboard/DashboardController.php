<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUrlRequest;
use App\Models\Url;
use App\Models\Visit;
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
        $urlVisitCount = app(Visit::class)->currentUserUrlVisitCount();

        return view('backend.dashboard', [
            'url'  => app(Url::class),
            'urlVisitCount' => numberAbbreviate($urlVisitCount),
        ]);
    }

    /**
     * Show shortened url details page
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
     * Update the destination URL
     *
     * @param UpdateUrlRequest $request \App\Http\Requests\UpdateUrlRequest
     * @param Url              $url     \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUrlRequest $request, Url $url)
    {
        $url->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);

        return to_route('dashboard')
            ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete shortened URLs
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
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
