<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Services\KeyService;
use App\Services\UrlService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     *
     * @param UrlService $urlSrvc \App\Services\UrlService
     */
    public function __construct(protected UrlService $urlSrvc)
    {
        //
    }

    /**
     * Show all user short URLs.
     */
    public function view()
    {
        $userSrvc = new UserService;
        $keySrvc = new KeyService;

        return view('backend.dashboard', [
            'shortUrlCount'        => $this->urlSrvc->shortUrlCount(),
            'shortUrlCountByMe'    => $this->urlSrvc->shortUrlCountOwnedBy(Auth::id()),
            'shortUrlCountByGuest' => $this->urlSrvc->shortUrlCountOwnedBy(),
            'clickCount'           => $this->urlSrvc->clickCount(),
            'clickCountFromMe'     => $this->urlSrvc->clickCountOwnedBy(Auth::id()),
            'clickCountFromGuest'  => $this->urlSrvc->clickCountOwnedBy(),
            'userCount'            => $userSrvc->userCount(),
            'guestCount'           => $userSrvc->guestCount(),
            'keyCapacity'          => $keySrvc->keyCapacity(),
            'keyRemaining'         => $keySrvc->keyRemaining(),
            'remainingPercentage'  => $keySrvc->keyRemainingInPercent(),
        ]);
    }

    /**
     * Show the long url edit page.
     *
     * @param mixed $key
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
     * @param Request $request \Illuminate\Http\Request
     * @param mixed   $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $url)
    {
        $this->urlSrvc->update($request->only('long_url', 'meta_title'), $url);

        return redirect()->route('dashboard')
                         ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param mixed $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($url)
    {
        $this->authorize('forceDelete', $url);

        $this->urlSrvc->delete($url);

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will produce a new unique random key.
     *
     * @param mixed $key
     */
    public function duplicate($key)
    {
        $this->urlSrvc->duplicate($key, Auth::id());

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
