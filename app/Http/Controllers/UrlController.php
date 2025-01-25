<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UrlHubLinkChecker;
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
        $url = Url::create([
            'user_id'   => auth()->id(),
            'destination' => $request->long_url,
            'title'     => app(Url::class)->getWebTitle($request->long_url),
            'keyword'   => app(Url::class)->getKeyword($request),
            'is_custom' => isset($request->custom_key) ? true : false,
            'user_sign' => app(User::class)->signature(),
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

        return view('backend.edit', ['url' => $url]);
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
            'title' => ['max:' . Url::TITLE_LENGTH],
        ]);

        $url->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);

        $flashType = 'flash_success';
        $message = __('Link updated successfully !');
        // if the user is not the author of the link
        if (!$url->author()->is(auth()->user())) {
            // if the author of the link is guest
            if ($url->user_id === null) {
                return to_route('dboard.allurl.u-guest')->with($flashType, $message);
            }

            return to_route('dboard.allurl')->with($flashType, $message);
        }

        return to_route('dashboard')->with($flashType, $message);
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
}
