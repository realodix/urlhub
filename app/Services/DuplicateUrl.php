<?php

namespace App\Services;

use App\Models\Url;

class DuplicateUrl
{
    /**
     * @param int|string|null $userId \Illuminate\Contracts\Auth\Guard::id()
     * @return bool \Illuminate\Database\Eloquent\Model::save()
     */
    public function execute(string $key, $userId, string $randomKey = null)
    {
        $randomKey = $randomKey ?? app(KeyGeneratorService::class)->generateRandomString();
        $shortenedUrl = Url::whereKeyword($key)->first();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $userId,
            'keyword'   => $randomKey,
            'is_custom' => false,
        ]);

        return $replicate->save();
    }
}
