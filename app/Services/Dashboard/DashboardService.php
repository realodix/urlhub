<?php

namespace App\Services\Dashboard;

use App\Url;

/**
 * @codeCoverageIgnore
 */
class DashboardService
{
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
