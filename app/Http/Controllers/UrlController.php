<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Services\QrCodeService;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;

class UrlController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('urlhublinkchecker', only: ['create'])];
    }

    /**
     * Shorten long URLs.
     *
     * @param StoreUrlRequest $request \App\Http\Requests\StoreUrlRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(StoreUrlRequest $request)
    {
        $url = Url::create([
            'user_id'   => auth()->id(),
            'destination' => $request->long_url,
            'title'     => app(Url::class)->getWebTitle($request->long_url),
            'keyword'   => app(Url::class)->getKeyword($request),
            'is_custom' => isset($request->custom_key) ? true : false,
            'user_sign' => app(User::class)->signature(),
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
        $data = [
            'url' => $url,
            'visit' => app(Visit::class),
            'visitsCount' => $url->visits()->count(),
            'qrCode' => app(QrCodeService::class)->execute($url->short_url),
        ];

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
        Gate::authorize('forceDelete', $url);

        $url->delete();

        return to_route('home');
    }
}
