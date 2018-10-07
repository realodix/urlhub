<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class GeneralUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkurl')->only('create');
    }

    public function create(Requests\StoreUrl $request)
    {
        $short_url = $request->short_url_custom ?? UrlHlp::url_generator();

        Url::create([
            'user_id'           => Auth::check() ? Auth::id() : 0,
            'long_url'          => $request->long_url,
            'long_url_title'    => UrlHlp::url_get_title($request->long_url),
            'short_url'         => UrlHlp::url_generator(),
            'short_url_custom'  => $request->short_url_custom ?? 0,
            'views'             => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$short_url);
    }

    public function urlRedirection($short_url)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $short_url)
                    ->orWhere('short_url_custom', $short_url)
                    ->firstOrFail();

        $url->increment('views');

        // Redirect to final destination
        return redirect()->away($url->long_url, 301);
    }

    public function checkCustomLinkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'short_url_custom'  => 'nullable|max:20|alpha_dash|unique:urls',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        return response()->json(['success'=>'Available']);
    }
}
