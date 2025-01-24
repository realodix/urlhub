<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UrlHubLinkChecker;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
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
     * @param Request $request \Illuminate\Http\Request
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        Gate::authorize('updateUrl', $url);

        $request->validate([
            'title'    => ['max:' . Url::TITLE_LENGTH],
            'long_url' => [
                'required', 'max:65535', new \App\Rules\NotBlacklistedDomain,
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*:\/\/[^\s]+$/', $value)) {
                        $fail('The :attribute field must be a valid URL or a valid deeplink.');
                    }
                },
            ],
        ]);

        $url->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);

        return to_route('dashboard')
            ->with('flash_success', __('Link changed successfully !'));
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
