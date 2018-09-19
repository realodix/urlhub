<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class GeneralUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkurl')->only('create');
    }

    public function create(Requests\StoreUrl $request)
    {
        $long_url = Input::get('long_url');
        $short_url = UrlHlp::url_generator();
        $short_url_custom = Input::get('short_url_custom');

        $shortUrl = $short_url_custom ?? $short_url;

        Url::create([
            'user_id'           => Auth::check() ? Auth::id() : 0,
            'long_url'          => $long_url,
            'long_url_title'    => UrlHlp::get_title($long_url),
            'short_url'         => $short_url,
            'short_url_custom'  => $short_url_custom ?? 0,
            'views'             => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$shortUrl);
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

    public function checkCustomLinkAvailability()
    {
        $short_url_custom = Input::get('short_url_custom');

        $link = Url::where('short_url_custom', $short_url_custom)->first();

        if ($link) {
            return 'unavailable';
        } else {
            return 'available';
        }
    }
}
