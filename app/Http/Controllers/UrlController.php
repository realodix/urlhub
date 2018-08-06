<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UrlController extends Controller
{
    public function create(Requests\StoreUrl $request)
    {
        $getLongURL = Input::get('long_url');

        $getUrlIdInDB = Url::orderBy('id', 'desc')->limit(1)->first();
        $hashids = new Hashids('', 6);
        if (empty($getUrlIdInDB)) {
            $shortURL = $hashids->encode(1);
        } else {
            $shortURL = $hashids->encode($getUrlIdInDB->id + 1);
        }

        $getLongUrlInDB = Url::where('long_url', $getLongURL)->first();
        if ($getLongUrlInDB == true) {
            return redirect('/view/'.$getLongUrlInDB->short_url)
                            ->with('msgLinkAlreadyExists', 'Link already exists');;
        }

        Url::create([
            'long_url'  => $getLongURL,
            'short_url' => $shortURL,
            'users_id'  => 0,
            'ip'        => $request->ip(),
        ]);

        return redirect('/view/'.$shortURL);
    }

    public function view($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        return view('short', [
            'long_url'      => $url->long_url,
            'short_url'     => $url->short_url,
            'created_at'    =>  Carbon::parse($url->created_at)->toDayDateTimeString(),
        ]);
    }

    public function url_redirection($link)
    {
        $url = Url::where('short_url', 'LIKE BINARY', $link)->firstOrFail();

        // Redirect to final destination
        return redirect()->away($url->long_url);
    }
}
