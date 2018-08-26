<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;

class AllUrlController extends Controller
{
    public function index()
    {
        $allurls = Url::all();

        return view('backend.all-url', [
            'allurls' => $allurls,
        ]);
    }
}
