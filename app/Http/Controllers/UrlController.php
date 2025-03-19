<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UrlHubLinkChecker;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Models\Visit;
use App\Services\QrCodeService;
use App\Services\UserService;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;

class UrlController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware(UrlHubLinkChecker::class, only: ['create'])];
    }

    /**
     * Shorten long URLs.
     *
     * @param StoreUrlRequest $request \App\Http\Requests\StoreUrlRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(StoreUrlRequest $request)
    {
        $userService = app(UserService::class);

        $url = Url::create([
            'user_id'   => auth()->id(),
            'user_type' => $userService->userType(),
            'destination' => $request->long_url,
            'title'     => app(Url::class)->getWebTitle($request->long_url),
            'keyword'   => app(Url::class)->getKeyword($request),
            'is_custom' => isset($request->custom_key) ? true : false,
            'forward_query' => auth()->check() ? true : false,
            'user_uid'  => $userService->signature(),
        ]);

        return to_route('link_detail', $url->keyword);
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
            'createdAt' => $url->created_at,
            'visit' => app(Visit::class),
            'visitsCount' => $url->visits()->count(),
            'qrCode' => app(QrCodeService::class)->execute($url->short_url),
        ];

        return view('frontend.short', $data);
    }

    /**
     * Show shortened url details page.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Url $url)
    {
        Gate::authorize('updateUrl', $url);

        $data = [
            'url' => $url,
            'createdAt' => $url->created_at->inUserTz(),
            'updatedAt' => $url->updated_at->inUserTz(),
        ];

        return view('backend.edit', $data);
    }

    /**
     * Update the destination URL.
     *
     * @param StoreUrlRequest $request \App\Http\Requests\StoreUrlRequest
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreUrlRequest $request, Url $url)
    {
        Gate::authorize('updateUrl', $url);

        $request->validate([
            'title' => ['max:'.Url::TITLE_LENGTH],
        ]);

        $url->update([
            'destination' => $request->long_url,
            'dest_android' => $request->dest_android,
            'dest_ios' => $request->dest_ios,
            'title' => $request->title,
            'forward_query' => $request->forward_query ? true : false,
            'expires_at' => $request->expires_at,
            'expired_clicks' => $request->expired_clicks,
            'expired_url' => $request->expired_url,
            'expired_notes' => $request->expired_notes,
        ]);

        return redirect()->back()
            ->with('flash_success', __('Link updated successfully !'));
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

        // if requst from shorten url details page, return to home
        if (request()->routeIs('link_detail.delete')) {
            return to_route('home');
        }

        return redirect()->back()
            ->with('flash_success', __('Link was successfully deleted.'));
    }

    /**
     * Display the expired link view.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function expiredLink(Url $url)
    {
        if (!$url->isExpired()) {
            return to_route('link_detail', $url->keyword);
        }

        return view('frontend.expired-link', ['url' => $url]);
    }
}
