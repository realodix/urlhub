<?php

namespace App\Http\Controllers;

use App\Helpers\UrlHelper;
use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UrlController extends Controller
{
    public function create(Requests\StoreUrl $request)
    {
        $UrlHelper = new UrlHelper();

        $getLongURL = Input::get('long_url');

        $short_url = $UrlHelper->urlGenerator();

        $getLongUrlInDB = Url::where('long_url', $getLongURL)->first();
        if ($getLongUrlInDB == true) {
            return redirect('/+'.$getLongUrlInDB->short_url)
                            ->with('msgLinkAlreadyExists', 'Link already exists');;
        }

        Url::create([
            'long_url'          => $getLongURL,
            'long_url_title'    => $UrlHelper->get_title($getLongURL),
            'short_url'         => $short_url,
            'users_id'          => 0,
            'ip'                => $request->ip(),
        ]);

        return redirect('/+'.$short_url);
    }

    public function view($link)
    {
        $UrlHelper = new UrlHelper();

        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        $qrCode = $UrlHelper->qrCodeGenerator($url->short_url);

        return view('short', [
            'long_url'          => $url->long_url,
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
