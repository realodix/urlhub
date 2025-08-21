<?php

namespace App\Settings;

use Illuminate\Validation\Rule;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /** @var int */
    const KEY_LEN_LOW = 2;

    /** @var int */
    const KEY_LEN_UP = 11;

    /** @var int */
    const CST_KEY_MIN_LEN_LOW = 2;

    /** @var int */
    const CST_KEY_MIN_LEN_UP = 29;

    /** @var int */
    const CST_KEY_MAX_LEN_LOW = 3;

    /** @var int */
    const CST_KEY_MAX_LEN_UP = 30;

    public bool $public_shortening;

    public bool $public_registration;

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
            'keyword_length' => [
                'required', 'numeric',
                Rule::numeric()->between(self::KEY_LEN_LOW, self::KEY_LEN_UP),
            ],
            'custom_keyword_min_length' => [
                'required', 'numeric',
                Rule::numeric()->between(self::CST_KEY_MIN_LEN_LOW, self::CST_KEY_MIN_LEN_UP),
            ],
            'custom_keyword_max_length' => [
                'required', 'numeric',
                Rule::numeric()->between(self::CST_KEY_MAX_LEN_LOW, self::CST_KEY_MAX_LEN_UP),
            ],
            'redirect_cache_max_age' => ['required', 'numeric', 'between:0,31536000'],
        ]);

        $this->fill([
            'public_shortening' => request()->boolean('public_shortening'),
            'public_registration' => request()->boolean('public_registration'),
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
