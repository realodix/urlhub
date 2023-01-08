<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\DuplicateUrl;
use App\Services\KeyGeneratorService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        public Url $url,
        public User $user,
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
     * @param mixed $key
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($key)
    {
        $url = Url::whereKeyword($key)->first();

        $this->authorize('updateUrl', $url);

        return view('backend.edit', ['url' => $url]);
    }

    /**
     * Update the destination URL
     *
     * @param Request $request \Illuminate\Http\Request
     * @param mixed   $url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $url)
    {
        $url->destination = $request->long_url;
        $url->title = $request->title;
        $url->save();

        return to_route('dashboard')
            ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete shortened URLs
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

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * @param mixed $key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($key)
    {
        app(DuplicateUrl::class)->execute($key, auth()->id());

        return redirect()->back()
            ->withFlashSuccess(__('The link has successfully duplicated.'));
    }
}
