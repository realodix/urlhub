<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'url'  => new Url,
            'user' => new User,
            'visit' => new Visit,
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
        $url = Url::whereKeyword($key)->firstOrFail();

        $this->authorize('updateUrl', $url);

        return view('backend.edit', compact('url'));
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

        return redirect()->route('dashboard')
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
        $url = new Url;
        $url->duplicate($key, Auth::id());

        return redirect()->back()
            ->withFlashSuccess(__('The link has successfully duplicated.'));
    }
}
