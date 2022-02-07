<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Services\UrlService;

class AllUrlController extends Controller
{
    /**
     * AllUrlController constructor.
     *
     * @param  UrlService  $urlSrvc  \App\Services\UrlService
     */
    public function __construct(protected UrlService $urlSrvc)
    {
        $this->middleware('role:admin');
    }

    /**
     * Show all short URLs created by all users.
     */
    public function view()
    {
        return view('backend.all-url');
    }

    /**
     * Delete a Short URL on user (Admin) request.
     *
     * @param  mixed  $url
     */
    public function delete($url)
    {
        $this->urlSrvc->delete($url);

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
