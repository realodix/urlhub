<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool $anyone_can_shorten;

    public bool $anyone_can_register;

    public int $key_len;

    public int $cst_key_min_len;

    public int $cst_key_max_len;

    public bool $autofill_link_title;

    public string $favicon_provider = 'google';

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
            'keyword_length' => ['required', 'numeric', 'between:2,11'],
            'custom_keyword_min_length' => ['required', 'numeric', 'between:2,29'],
            'custom_keyword_max_length' => ['required', 'numeric', 'between:3,30'],
            'redirect_cache_max_age' => ['required', 'numeric', 'between:0,31536000'],
        ]);

        $this->fill([
            'anyone_can_shorten' => request()->boolean('anyone_can_shorten'),
            'anyone_can_register' => request()->boolean('anyone_can_register'),
            'key_len' => request()->input('keyword_length'),
            'cst_key_min_len' => request()->input('custom_keyword_min_length'),
            'cst_key_max_len' => request()->input('custom_keyword_max_length'),
            'autofill_link_title' => request()->boolean('autofill_link_title'),
            'favicon_provider' => request()->input('favicon_provider'),
            'forward_query' => request()->boolean('forward_query'),
            'redirect_cache_max_age' => request()->input('redirect_cache_max_age'),
            'track_bot_visits' => request()->boolean('track_bot_visits'),
        ]);

        $this->save();
    }
}
