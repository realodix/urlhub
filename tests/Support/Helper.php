<?php

namespace Tests\Support;

use App\Models\Url;

class Helper
{
    /**
     * @see \App\Http\Controllers\LinkController::update()
     */
    public static function updateLinkData(Url $model, array $replacements): array
    {
        $initialData = [
            'title' => $model->title,
            'long_url' => $model->destination,
            'dest_android' => $model->dest_android,
            'dest_ios' => $model->dest_ios,
            'forward_query' => $model->forward_query,
        ];

        return array_merge($initialData, $replacements);
    }
}
