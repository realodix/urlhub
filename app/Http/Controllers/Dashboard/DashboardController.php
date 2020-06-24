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
     * @var dashboardService
     */
    protected $dashboardService;

    /**
     * DashboardController constructor.
     *
     * @param Url $url
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show all user short URLs.
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
    public function dataTable()
    {
        return $this->dashboardService->dataTable();
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
     * @param \Illuminate\Http\Request                  $request
     * @param \App\Services\Dashboard\DashboardService  $dashboardService
     * @param \App\Url                                  $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        $this->dashboardService->update($request->only('long_url', 'meta_title'), $url);

        return redirect()->route('dashboard')
                         ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param \App\Url $url
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
     */
    public function duplicate($key, DashboardService $dashboardService)
    {
        $authId = Auth::id();
        $this->dashboardService->duplicate($key, $authId);

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
