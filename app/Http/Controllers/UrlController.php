<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Facades\App\Helpers\Hlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UrlController extends Controller
{
    public function create(Requests\StoreUrl $request)
    {
        $getLongURL = Input::get('long_url');

        $short_url = Hlp::urlGenerator();

        $getLongUrlInDB = Url::where('long_url', $getLongURL)->first();
        if ($getLongUrlInDB == true) {
            return redirect('/+'.$getLongUrlInDB->short_url)->with('msgLinkAlreadyExists', 'Link already exists');;
        }

        Url::create([
            'long_url'          => $getLongURL,
            'long_url_title'    => Hlp::get_title($getLongURL),
            'short_url'         => $short_url,
            'users_id'          => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$short_url);
    }

    public function view($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        $qrCode = Hlp::qrCodeGenerator($url->short_url);

        return view('short', [
            'long_url_href'     => $url->long_url,
            'long_url'          => Hlp::url_limit($url->long_url),
            'long_url_title'    => $url->long_url_title,
            'short_url'         => $url->short_url,
            'qrCodeData'        => $qrCode->getContentType(),
            'qrCodebase64'      => $qrCode->generate(),
            'created_at'        => Carbon::parse($url->created_at)->toDayDateTimeString(),
        ]);
    }

    public function url_redirection($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        // Redirect to final destination
        return redirect()->away($url->long_url);
    }
}
