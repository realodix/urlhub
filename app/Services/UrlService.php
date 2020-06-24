<?php

namespace App\Services;

use App\Url;

class UrlService
{
    public function shortenUrl($request, $key, $authId)
    {
        Url::create([
            'user_id'    => $authId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => \request()->ip(),
        ]);
    }

    /**
     * @param string $key
     */
    public function duplicate($key, $randomKey, $authId)
    {
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();
    }
}
