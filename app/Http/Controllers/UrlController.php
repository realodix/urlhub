<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrl;
use App\Models\Url;
use App\Services\QrCodeService;
use App\Services\UHubLinkService;

class UrlController extends Controller
{
    /**
     * UrlController constructor.
     */
    public function __construct(
        public Url $url,
        public UHubLinkService $uHubLinkService,
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
        $url = $this->uHubLinkService->create($request);

        return to_route('su_detail', $url->keyword);
    }

    /**
     * View the shortened URL details.
     *
     * @codeCoverageIgnore
     *
     * @param string $urlKey A unique key for the shortened URL
     * @return \Illuminate\Contracts\View\View
     */
    public function showDetail(string $urlKey)
    {
        $url = Url::with('visits')->whereKeyword($urlKey)->firstOrFail();
        $data = [
            'url' => $url,
            'visit' => new \App\Models\Visit,
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

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will generated a new unique random
     * key.
     *
     * @param string $urlKey A unique key for the shortened URL
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(string $urlKey)
    {
        $this->uHubLinkService->duplicate($urlKey);

        return to_route('su_detail', $this->uHubLinkService->new_keyword)
            ->withFlashSuccess(__('The link has successfully duplicated.'));
    }
}
