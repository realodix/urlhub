<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Http\Request;

class UpdateShortenedUrl
{
    /**
     * @return bool
     */
    public function execute(Request $request, Url $url)
    {
        return $url->update([
            'destination' => $request->long_url,
            'title' => $request->title,
        ]);
    }
}
