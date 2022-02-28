<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyService;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show all user short URLs.
     */
    public function view()
    {
        $user = new User;
        $keySrvc = new KeyService;
        $urlSrvc = new UrlService;

        return view('backend.dashboard', [
            'totalUrl'         => $urlSrvc->totalUrl(),
            'urlCount_Me'      => $urlSrvc->urlCount(Auth::id()),
            'urlCount_Guest'   => $urlSrvc->urlCount(),
            'totalClick'       => $urlSrvc->totalClick(),
            'clickCount_Me'    => $urlSrvc->clickCount(Auth::id()),
            'clickCount_Guest' => $urlSrvc->clickCount(),
            'userCount'        => User::count(),
            'guestCount'       => $user->guestCount(),
            'keyCapacity'      => $keySrvc->keyCapacity(),
            'keyRemaining'     => $keySrvc->keyRemaining(),
            'keyRemaining_Percent' => $keySrvc->keyRemainingInPercent(),
        ]);
    }

    /**
     * Show the long url edit page.
     *
     * @param  mixed  $key
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
     * @param  Request  $request  \Illuminate\Http\Request
     * @param  mixed  $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $url)
    {
        $urlSrvc = new UrlService;

        $urlSrvc->update($request->only('long_url', 'meta_title'), $url);

        return redirect()->route('dashboard')
                         ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param  mixed  $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($url)
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
     * @param  mixed  $key
     */
    public function duplicate($key)
    {
        $url = new Url;
        $url->duplicate($key, Auth::id());

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
