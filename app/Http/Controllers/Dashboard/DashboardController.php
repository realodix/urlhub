<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Url;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show users all their Short URLs.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $url = new Url;
        $user = new User;
        $kwCapacity = $url->keywordCapacity();

        return view('backend.dashboard', [
            'shortUrlCount'        => $url->shortUrlCount(),
            'shortUrlCountByMe'    => $url->shortUrlCountOwnedBy(Auth::id()),
            'shortUrlCountByGuest' => $url->shortUrlCountOwnedBy(),
            'clickCount'           => $url->clickCount(),
            'clickCountFromMe'     => $url->clickCountOwnedBy(Auth::id()),
            'clickCountFromGuest'  => $url->clickCountOwnedBy(),
            'userCount'            => $user->userCount(),
            'guestCount'           => $user->guestCount(),
            'capacity'             => $kwCapacity,
            'remaining'            => $url->keywordRemaining(),
            'remaining_percent'    => remainingPercentage($url->shortUrlCount(), $kwCapacity),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function dataTable(DashboardService $dashboardService)
    {
        return $dashboardService->dataTable();
    }

    /**
     * Show the long url edit page.
     *
     * @param string $key
     *
     * @return \Illuminate\View\View
     */
    public function edit($key)
    {
        $url = Url::whereKeyword($key)->firstOrFail();

        $this->authorize('updateUrl', $url);

        return view('backend.edit', compact('url'));
    }

    /**
     * Update the long url that was previously set to the new long url.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Url                 $url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        $url->long_url = $request->input('long_url');
        $url->meta_title = $request->input('meta_title');
        $url->save();

        return redirect()->route('dashboard')
                         ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param \App\Url $url
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        $this->authorize('forceDelete', $url);

        $url->delete();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will produce a new unique random key.
     *
     * @param string $key
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function duplicate($key)
    {
        $url = new Url;
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => Auth::id(),
            'keyword'   => $url->randomKeyGenerator(),
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
