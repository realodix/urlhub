<?php

namespace App\Models;

use App\Enums\UserType;
use App\Http\Requests\StoreUrlRequest;
use App\Services\KeyGeneratorService;
use App\Settings\GeneralSettings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property UserType $user_type
 * @property string $keyword
 * @property bool $is_custom
 * @property string $destination
 * @property string $title
 * @property bool $forward_query
 * @property string $user_uid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User $author
 * @property Visit $visits
 * @property string $short_url
 */
class Url extends Model
{
    /** @use HasFactory<\Database\Factories\UrlFactory> */
    use HasFactory;

    /** @var null */
    const GUEST_ID = null;

    /** @var int */
    const TITLE_LENGTH = 255;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'keyword',
        'is_custom',
        'destination',
        'title',
        'forward_query',
        'user_uid',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'user_type' => UserType::class,
            'is_custom' => 'boolean',
            'forward_query' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user that owns the Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault([
                'name' => 'Guest Author',
            ]);
    }

    /**
     * Get the visits for the Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Visit, $this>
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    protected function userId(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value === 0 ? self::GUEST_ID : $value,
        );
    }

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attr) => url('/'.$attr['keyword']),
        );
    }

    protected function destination(): Attribute
    {
        return Attribute::make(
            set: fn($value) => rtrim($value, '/'),
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (mb_strlen($value) > self::TITLE_LENGTH) {
                    return mb_strimwidth($value, 0, self::TITLE_LENGTH, '...');
                }

                return $value;
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    public function getKeyword(StoreUrlRequest $request): string
    {
        $keyGen = app(KeyGeneratorService::class);

        return $request->custom_key ?? $keyGen->generate($request->long_url);
    }

    /**
     * Get the title from the web.
     *
     * @param string $value A webpage's URL
     */
    public function getWebTitle(string $value): string
    {
        $uri = \Illuminate\Support\Uri::of($value);
        $defaultTitle = $uri->host().' - Untitled';

        if (app(GeneralSettings::class)->retrieve_web_title) {
            try {
                $title = app(\Embed\Embed::class)->get($value)->title ?? $defaultTitle;
            } catch (\Exception) {
                // If failed or not found, then return "{domain_name} - Untitled"
                $title = $defaultTitle;
            }

            return $title;
        }

        return 'No Title';
    }

    /**
     * The number of short links created by the currently logged-in user.
     */
    public function authUserLinks(): int
    {
        return self::where('user_id', auth()->id())
            ->count();
    }

    /**
     * The number of short links created by all registered users.
     */
    public function userLinks(): int
    {
        return self::where('user_type', UserType::User)
            ->count();
    }

    /**
     * The number of short links created by all guest users.
     */
    public function guestLinks(): int
    {
        return self::where('user_type', UserType::Guest)
            ->count();
    }
}
