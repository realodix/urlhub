<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AllUrlService;
use App\Url;

class AllUrlController extends Controller
{
    /**
     * @var allUrlService
     */
    protected $allUrlService;

    /**
     * AllUrlController constructor.
     */
    public function __construct(AllUrlService $allUrlService)
    {
        $this->middleware('role:admin');
        $this->allUrlService = $allUrlService;
    }

    /**
     * Show all short URLs created by all users.
     */
    public function view()
    {
        return view('backend.all-url');
    }

    /**
     * @codeCoverageIgnore
     */
    public function dataTable()
    {
        return $this->allUrlService->dataTable();
    }

    /**
     * Delete a Short URL on user (Admin) request.
     *
     * @param \App\Url $url
     */
    public function delete(Url $url)
    {
        $url->delete();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
