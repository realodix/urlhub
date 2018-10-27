<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Rules\Lowercase;
use App\Url;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class GeneralUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('plurlinkchecker')->only('create');
    }

    public function create(Requests\StoreUrl $request)
    {
        $link_generator = UrlHlp::link_generator();
        $short_url = $request->short_url_custom ?? $link_generator;

        Url::create([
            'user_id'          => Auth::check() ? Auth::id() : 0,
            'long_url'         => $request->long_url,
            'meta_title'       => $request->long_url,
            'short_url'        => $request->short_url_custom ? sha1($link_generator) : $link_generator,
            'short_url_custom' => $request->short_url_custom ?? '',
            'views'            => 0,
            'ip'               => $request->ip(),
        ]);

        return redirect('/+'.$short_url);
    }

    public function urlRedirection($short_url)
    {
        $url = Url::where('short_url', $short_url)
                    ->orWhere('short_url_custom', $short_url)
                    ->firstOrFail();

        $url->increment('views');

        // Redirect to final destination
        return redirect()->away($url->long_url, 301);
    }

    public function checkCustomLinkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'short_url_custom'  => ['nullable', 'max:20', 'alpha_dash', 'unique:urls', new Lowercase],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        return response()->json(['success'=>'Available']);
    }
}
