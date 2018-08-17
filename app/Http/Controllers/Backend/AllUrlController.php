<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Facades\App\Helpers\Hlp;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllUrlController extends Controller
{
    public function index()
    {
        $allurls = Url::all();

        return view('backend.all-url',[
            'allurls' => $allurls,
        ]);
    }
}
