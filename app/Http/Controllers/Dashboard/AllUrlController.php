<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class AllUrlController extends Controller
{
    /**
     * AllUrlController constructor.
     */
    public function __construct()
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
     * @param mixed $url
     */
    public function delete($url)
    {
        $url->delete();

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
