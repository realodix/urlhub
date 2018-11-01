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

    /**
     * @param \App\Http\Requests\StoreUrl $request
     */
    public function create(Requests\StoreUrl $request)
    {
        $generated_key = UrlHlp::key_generator();
        $url_key = $request->custom_url_key ?? $generated_key;

        Url::create([
            'user_id'    => Auth::id(),
            'long_url'   => $request->long_url,
            'meta_title' => $request->long_url,
            'url_key'    => $request->custom_url_key ?? $generated_key,
            'is_custom'  => $request->custom_url_key ? 1 : 0,
            'ip'         => $request->ip(),
        ]);

        return redirect('/+'.$url_key);
    }

    /**
     * @param string $url_key
     */
    public function urlRedirection($url_key)
    {
        $url = Url::where('url_key', $url_key)
                    ->firstOrFail();

        // $url->increment('views');
        Url::where('url_key', $url_key)
            ->increment('views');

        // Redirect to final destination
        return redirect()->away($url->long_url, 301);
    }

    public function checkCustomLinkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url_key'  => ['nullable', 'max:20', 'alpha_dash', 'unique:urls', new Lowercase],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        return response()->json(['success'=>'Available']);
    }
}
