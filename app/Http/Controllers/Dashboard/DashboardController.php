<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrl;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyGeneratorService;
use App\Services\UrlService;

class DashboardController extends Controller
{
    /**
     * Show all user short URLs.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.dashboard', [
            'url'  => app(Url::class),
            'user' => app(User::class),
            'keyGeneratorService' => app(KeyGeneratorService::class),
        ]);
    }

    /**
     * Show shortened url details page
     *
     * @param string $urlKey A unique key to identify the shortened URL
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
     * @param StoreUrl $request \App\Http\Requests\StoreUrl
     * @param Url      $hash_id \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreUrl $request, Url $hash_id)
    {
        $hash_id->update([
            'destination' => $request->long_url,
            'title'       => $request->title,
        ]);

        return to_route('dashboard')
            ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete shortened URLs
     *
     * @param Url $hash_id \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $hash_id)
    {
        $this->authorize('forceDelete', $hash_id);

        $hash_id->delete();

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
