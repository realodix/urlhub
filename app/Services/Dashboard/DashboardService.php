<?php

namespace App\Services\Dashboard;

use App\Url;

/**
 * @codeCoverageIgnore
 */
class DashboardService
{
    /**
     * @param array     $request
     * @param string    $url
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
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $url->randomKeyGenerator(),
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();
    }
}
