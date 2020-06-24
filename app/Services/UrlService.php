<?php

namespace App\Services;

use App\Url;

class UrlService
{
    /**
     * @param string $key
     * @return \Illuminate\Http\RedirectResponse
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
