<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Models\{Url, User, Visit};
use App\Services\QrCodeService;

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
        $url = Url::create([
            'user_id'     => auth()->id(),
            'destination' => $request->long_url,
            'title'       => app(Url::class)->getWebTitle($request->long_url),
            'keyword'     => app(Url::class)->getKeyword($request),
            'is_custom'   => $request->custom_key ? true : false,
            'user_sign'   => app(User::class)->signature(),
        ]);

        return to_route('su_detail', $url->keyword);
    }

    /**
     * View the shortened URL details.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View
     */
    public function showDetail(Url $url)
    {
        $url->with('visits');
        $data = [
            'url'   => $url,
            'visit' => app(Visit::class),
        ];

        if (config('urlhub.qrcode')) {
            $qrCode = app(QrCodeService::class)->execute($url->short_url);

            $data = array_merge($data, ['qrCode' => $qrCode]);
        }

        return view('frontend.short', $data);
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        $this->authorize('forceDelete', $url);

        $url->delete();

        return to_route('home');
    }
}
