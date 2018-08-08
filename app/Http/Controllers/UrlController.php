<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Facades\App\Helpers\Hlp;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UrlController extends Controller
{
    public function create(Requests\StoreUrl $request)
    {
        $getLongURL = Input::get('long_url');

        $short_url = UrlHlp::url_generator();

        $getLongUrlInDB = Url::where('long_url', $getLongURL)->first();
        if ($getLongUrlInDB == true) {
            return redirect('/+'.$getLongUrlInDB->short_url)->with('msgLinkAlreadyExists', 'Link already exists');
        }

        if (UrlHlp::domainBlocked($getLongURL) == true) {
            return redirect('/')->with('msgDomainBlocked', 'Sorry, we cannot continue. We believe the URL you submitted has been shortened by a similar service.');
        }

        Url::create([
            'users_id'          => 0,
            'long_url'          => $getLongURL,
            'long_url_title'    => UrlHlp::get_title($getLongURL),
            'short_url'         => $short_url,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$short_url);
    }

    public function view($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        $qrCode = Hlp::qrCodeGenerator($url->short_url);

        return view('short', [
            'long_url'          => UrlHlp::url_limit(UrlHlp::urlToDomain(url($url->long_url))),
            'long_url_href'     => $url->long_url,
            'long_url_title'    => $url->long_url_title,
            'short_url'         => UrlHlp::urlToDomain(url('/', $url->short_url)),
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
