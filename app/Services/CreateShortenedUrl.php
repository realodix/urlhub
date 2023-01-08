<?php

namespace App\Services;

use App\Models\Url;

class CreateShortenedUrl
{
    /**
     * @return \App\Models\Url
     */
    public function execute(array $data)
    {
        return Url::create([
            'user_id'     => $data['user_id'],
            'destination' => $data['destination'],
            'title'       => $data['title'],
            'keyword'     => $data['keyword'],
            'is_custom'   => $data['is_custom'],
            'ip'          => $data['ip'],
        ]);
    }
}
