<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UrlHubLinkChecker;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Services\LinkService;
use App\Services\QrCodeService;
use App\Services\RedirectService;
use App\Services\UserService;
use App\Services\VisitService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class LinkController extends Controller implements HasMiddleware
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
        $url = new Url;
        $userService = app(UserService::class);
        $linkService = app(LinkService::class);

        $url->user_id = (int) auth()->id();
        $url->keyword = $linkService->getKeyword($request);
        $url->destination = $request->long_url;
        $url->title = $linkService->getWebTitle($request->long_url);
        $url->forward_query = auth()->check() ? true : false;
        $url->is_custom = isset($request->custom_key) ? true : false;
        $url->user_type = $userService->userType();
        $url->user_uid = $userService->signature();
        $url->save();

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
            'visitsCount' => $url->visits()->count(),
            'visitService' => app(VisitService::class),
            'qrCode' => app(QrCodeService::class)->execute($url->short_url),
        ];

        return view('frontend.short', $data);
    }

    /**
     * Show shortened url details page.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Url $url)
    {
        Gate::authorize('authorOrAdmin', $url);

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
        Gate::authorize('authorOrAdmin', $url);

        $request->validate([
            'title' => ['max:'.Url::TITLE_LENGTH],
        ]);

        $url->title = $request->title;
        $url->destination = $request->long_url;
        $url->dest_android = $request->dest_android;
        $url->dest_ios = $request->dest_ios;
        $url->expires_at = $request->expires_at;
        $url->expired_clicks = $request->expired_clicks;
        $url->expired_url = $request->expired_url;
        $url->expired_notes = $request->expired_notes;
        $url->forward_query = $request->forward_query ? true : false;
        $url->save();

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
        Gate::authorize('authorOrAdmin', $url);

        $url->delete();

        // if requst from shorten url details page, return to home
        if (request()->routeIs('link_detail.delete')) {
            return to_route('home');
        }

        return redirect()->back()
            ->with('flash_success', __('Link was successfully deleted.'));
    }

    /**
     * Displays the password form for a link.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function password(Url $url)
    {
        if (!$url->password) {
            return to_route('link_detail', $url->keyword);
        }

        return view('frontend.linkpassword', ['url' => $url]);
    }

    /**
     * Validate the given password against the stored one for the given URL.
     * If it matches, redirect the user to the long URL.
     * If it doesn't, redirect the user back with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(Request $request, Url $url)
    {
        if (Hash::check($request->password, $url->password)) {
            return app(RedirectService::class)->execute($url);
        }

        return back()->withErrors(['password' => 'The password is incorrect.']);
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
