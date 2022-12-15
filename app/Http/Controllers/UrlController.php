<?php

namespace App\Http\Controllers;

use App\Actions\QrCode;
use App\Http\Requests\StoreUrl;
use App\Models\Url;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * UrlController constructor.
     */
    public function __construct()
    {
        $this->middleware('urlhublinkchecker')->only('create');
    }

    /**
     * Shorten long URLs.
     *
     * @param StoreUrl $request \App\Http\Requests\StoreUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(StoreUrl $request)
    {
        $url = (new Url)->shortenUrl($request, Auth::id());

        return redirect()->route('su_detail', $url->keyword);
    }

    /**
     * View the shortened URL details.
     *
     * @codeCoverageIgnore
     *
     * @param string $key
     * @return \Illuminate\View\View
     */
    public function showDetail($key)
    {
        $url = Url::with('visit')->whereKeyword($key)->firstOrFail();

        if (config('urlhub.qrcode')) {
            $qrCode = (new QrCode)->process($url->short_url);

            return view('frontend.short', compact(['qrCode']), ['url' => $url]);
        }

        return view('frontend.short', ['url' => $url]);
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param mixed $url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($url)
    {
        $this->authorize('forceDelete', $url);

        $url->delete();

        return redirect()->route('home');
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will generated a new unique random
     * key.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(string $key)
    {
        $url = new Url;
        $randomKey = $url->randomString();
        $url->duplicate($key, Auth::id(), $randomKey);

        return redirect()->route('su_detail', $randomKey)
            ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
