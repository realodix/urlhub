<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Facades\App\Helpers\Hlp;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UrlController extends Controller
{
    public function create(Requests\StoreUrl $request)
    {
        $long_url = Input::get('long_url');
        $short_url = UrlHlp::url_generator();
        $short_url_custom = Input::get('short_url_custom');

        $shortUrl = $short_url_custom ?? $short_url;

        Url::create([
            'users_id'          => Auth::check() ? Auth::id() : 0,
            'long_url'          => $long_url,
            'long_url_title'    => UrlHlp::get_title($long_url),
            'short_url'         => $short_url,
            'short_url_custom'  => $short_url_custom ?? 0,
            'views'             => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$shortUrl);
    }

    public function view($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)
                    ->orWhere('short_url_custom', $link)
                    ->firstOrFail();

        if ($url->short_url_custom) {
            $blabla = $url->short_url_custom;
        } else {
            $blabla = $url->short_url;
        }

        $qrCode = Hlp::qrCodeGenerator($url->short_url);

        return view('short', [
            'long_url'          => UrlHlp::urlToDomain(UrlHlp::url_limit($url->long_url)),
            'long_url_href'     => $url->long_url,
            'long_url_title'    => $url->long_url_title,
            'views'             => $url->views,
            'short_url'         => UrlHlp::urlToDomain(url('/', $blabla)),
            'short_url_href'    => url('/', $blabla),
            'qrCodeData'        => $qrCode->getContentType(),
            'qrCodebase64'      => $qrCode->generate(),
            'created_at'        => Carbon::parse($url->created_at)->toDayDateTimeString(),
        ]);
    }

    public function url_redirection($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)
                    ->orWhere('short_url_custom', $link)
                    ->firstOrFail();

        $url->increment('views');

        // Redirect to final destination
        return redirect()->away($url->long_url);
    }
}
