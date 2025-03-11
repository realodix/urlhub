<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool $anyone_can_shorten;

    public bool $anyone_can_register;

    public int $keyword_length;

    public int $custom_keyword_min_length;

    public int $custom_keyword_max_length;

    public bool $retrieve_web_title;

    public bool $forward_query;

    public int $redirect_cache_max_age;

    public bool $track_bot_visits;

    public static function group(): string
    {
        return 'general';
    }

    public function update(): void
    {
        request()->validate([
            'keyword_length'            => ['required', 'numeric', 'between:2,20'],
            'custom_keyword_min_length' => ['required', 'numeric', 'between:2,19'],
            'custom_keyword_max_length' => ['required', 'numeric', 'between:3,20'],
            'redirect_cache_max_age'    => ['required', 'numeric', 'between:0,31536000'],
        ]);

        $this->fill([
            'anyone_can_shorten'        => request()->boolean('anyone_can_shorten'),
            'anyone_can_register'       => request()->boolean('anyone_can_register'),
            'keyword_length'            => request()->input('keyword_length'),
            'custom_keyword_min_length' => request()->input('custom_keyword_min_length'),
            'custom_keyword_max_length' => request()->input('custom_keyword_max_length'),
            'retrieve_web_title'        => request()->boolean('retrieve_web_title'),
            'forward_query'             => request()->boolean('forward_query'),
            'redirect_cache_max_age'    => request()->input('redirect_cache_max_age'),
            'track_bot_visits'          => request()->boolean('track_bot_visits'),
        ]);

        $this->save();
    }
}
