<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use App\Services\CreateShortenedUrl;
use App\Services\DuplicateUrl;
use App\Services\KeyGeneratorService;
use App\Services\QrCodeService;

class UrlController extends Controller
{
    /**
     * UrlController constructor.
     */
    public function __construct(
        public Url $url,
    ) {
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
        $url = app(CreateShortenedUrl::class)->execute($request);

        return to_route('su_detail', $url->keyword);
    }

    /**
     * View the shortened URL details.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showDetail(string $urlKey)
    {
        $url = Url::with('visit')->whereKeyword($urlKey)->firstOrFail();
        $data = ['url' => $url, 'visit' => new \App\Models\Visit];

        if (config('urlhub.qrcode')) {
            $qrCode = app(QrCodeService::class)->execute($url->short_url);

            $data = array_merge($data, ['qrCode' => $qrCode]);
        }

        return view('frontend.short', $data);
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

        return to_route('home');
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
        $randomKey = app(KeyGeneratorService::class)->generateRandomString();
        app(DuplicateUrl::class)->execute($key, auth()->id(), $randomKey);

        return to_route('su_detail', $randomKey)
            ->withFlashSuccess(__('The link has successfully duplicated.'));
    }
}
