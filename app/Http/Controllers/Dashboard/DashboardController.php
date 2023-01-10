<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyGeneratorService;
use App\Services\UHubLinkService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        public Url $url,
        public User $user,
        public UHubLinkService $uHubLinkService,
    ) {
    }

    /**
     * Show all user short URLs.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.dashboard', [
            'url'  => $this->url,
            'user' => $this->user,
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }

    /**
     * Show shortened url details page
     *
     * @param string $urlKey A unique key for the shortened URL
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $urlKey)
    {
        $url = Url::whereKeyword($urlKey)->firstOrFail();

        $this->authorize('updateUrl', $url);

        return view('backend.edit', ['url' => $url]);
    }

    /**
     * Update the destination URL
     *
     * @param Request $request \Illuminate\Http\Request
     * @param Url     $url     \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        $this->uHubLinkService->update($request, $url);

        return to_route('dashboard')
            ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete shortened URLs
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

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * @param string $urlKey A unique key for the shortened URL
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($urlKey)
    {
        $this->uHubLinkService->duplicate($urlKey);

        return redirect()->back()
            ->withFlashSuccess(__('The link has successfully duplicated.'));
    }
}
