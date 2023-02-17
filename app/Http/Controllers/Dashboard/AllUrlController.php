<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;

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
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.all-url');
    }

    /**
     * Delete a Short URL on user (Admin) request.
     *
     * @param Url $hash_id \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Url $hash_id)
    {
        $hash_id->delete();

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}
