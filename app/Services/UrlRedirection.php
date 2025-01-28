<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;

class UrlRedirection
{
    /**
     * Execute the HTTP redirect and return the redirect response.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(Url $url)
    {
        $settings = app(GeneralSettings::class);
        $statusCode = $settings->redirect_status_code;
        $maxAge = $settings->redirect_cache_max_age;
        $headers = ['Cache-Control' => sprintf('private,max-age=%s', $maxAge)];
        if ($maxAge === 0) {
            $headers = ['Cache-Control' => 'max-age=0, must-revalidate'];
        }

        return redirect()->away($url->destination, $statusCode, $headers);
    }
}
