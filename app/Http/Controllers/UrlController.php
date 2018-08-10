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
        $shortUrlCustom = Input::get('short_url_custom');
        $short_url = UrlHlp::url_generator();

        // if (UrlHlp::domainBlocked($getLongURL) == true) {
        //     return back()->with('msgDomainBlocked', 'Sorry, we cannot continue. We believe the URL you submitted has been shortened by a similar service.');
        // }

        $getUrlInDB = Url::where('long_url', $getLongURL)->first();
        if ($getUrlInDB == true) {
            return redirect('/+'.$getUrlInDB->short_url)->with('msgLinkAlreadyExists', 'Link already exists');
        }

        $cst = Url::where('short_url_custom', $shortUrlCustom)->first();
        if ($cst == true) {
            return back()->with('cst_exist', 'Sorry');
        }

        if ($shortUrlCustom) {
            $blabla = $shortUrlCustom;
        } else {
            $blabla = $short_url;
        }

        Url::create([
            'users_id'          => 0,
            'long_url'          => $getLongURL,
            'long_url_title'    => UrlHlp::get_title($getLongURL),
            'short_url'         => $short_url,
            'short_url_custom'  => $shortUrlCustom ?? 0,
            'views'             => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$blabla);
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
            'long_url'          => UrlHlp::url_limit(UrlHlp::urlToDomain(url($url->long_url))),
            'long_url_href'     => $url->long_url,
            'long_url_title'    => $url->long_url_title,
            'views'             => $url->views,
            'short_url'         => UrlHlp::urlToDomain(url('/', $blabla)),
            'short_url_href'    => $blabla,
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
