<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;

class AllUrlController extends Controller
{
    public function index()
    {
        return view('backend.all-url', [
            'allurls'   => Url::all(),
        ]);
    }

    public function delete($id)
    {
        Url::destroy($id);

        return redirect()->back();
    }
}
