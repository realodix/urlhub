<?php

namespace App\Services;

use App\Url;

class UrlService
{
    public function shortenUrl($request, $authId)
    {
        $url = new Url;
        $key = $request['custom_key'] ?? $url->randomKeyGenerator();

        Url::create([
            'user_id'    => $authId,
            'long_url'   => $request['long_url'],
            'meta_title' => $request['long_url'],
            'keyword'    => $key,
            'is_custom'  => $request['custom_key'] ? 1 : 0,
            'ip'         => \request()->ip(),
        ]);

        return $key;
    }

    /**
     * @param array  $request
     * @param string $url
     */
    public function update($data, $url)
    {
        $url->long_url = $data['long_url'];
        $url->meta_title = $data['meta_title'];
        $url->save();
    }

    /**
     * @param string $key
     */
    public function duplicate($key, $authId)
    {
        $url = new Url;
        $randomKey = $url->randomKeyGenerator();
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $randomKey,
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();

        return $randomKey;
    }
}
