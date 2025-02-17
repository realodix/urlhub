<?php

namespace Tests\Support;

use App\Models\Url;
use Illuminate\Validation\Validator;

class Helper
{
    /**
     * @see \App\Http\Controllers\UrlController::update()
     */
    public static function updateLinkData(Url $model, array $replacements): array
    {
        $initialData = [
            'title' => $model->title,
            'long_url' => $model->destination,
            'forward_query' => $model->forward_query,
        ];

        return array_merge($initialData, $replacements);
    }

    public static function validator(array $data, array $rules): Validator
    {
        $trans = new \Illuminate\Translation\Translator(
            new \Illuminate\Translation\ArrayLoader, 'en',
        );

        return new Validator($trans, $data, $rules);
    }
}
