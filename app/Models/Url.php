<?php

namespace App\Models;

use App\Http\Requests\StoreUrlRequest;
use App\Services\KeyGeneratorService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int            $id
 * @property int|null       $user_id
 * @property string         $keyword
 * @property bool           $is_custom
 * @property string         $destination
 * @property string         $title
 * @property string         $user_sign
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User           $author
 * @property Visit          $visits
 * @property string         $short_url
 * @property int            $clicks
 * @property int            $uniqueClicks
 * @property-read int       $visit_count
 * @property-read int       $unique_visit_count
 */
class Url extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    const GUEST_ID = null;

    const TITLE_LENGTH = 255;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'keyword',
        'is_custom',
        'destination',
        'title',
        'user_sign',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{user_id: 'integer', is_custom: 'boolean'}
     */
    protected function casts(): array
    {
        return [
            'user_id'   => 'integer',
            'is_custom' => 'boolean',
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
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the visits for the Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
            set: fn ($value) => $value === 0 ? self::GUEST_ID : $value,
        );
    }

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => url('/'.$attr['keyword']),
        );
    }

    protected function destination(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => rtrim($value, '/'),
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (mb_strlen($value) > self::TITLE_LENGTH) {
                    // $limit minus 3 because Str::limit() adds 3 extra characters.
                    return str($value)->limit(self::TITLE_LENGTH - 3, '...');
                }

                return $value;
            },
        );
    }

    protected function clicks(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $this->numberOfClicks($attr['id']),
        );
    }

    /**
     * @deprecated https://github.com/realodix/urlhub/pull/1003
     */
    protected function uniqueClicks(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $this->numberOfClicks($attr['id'], unique: true),
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
     * Get the title from the web
     *
     * @param string $value A webpage's URL
     */
    public function getWebTitle(string $value): string
    {
        $spatieUrl = \Spatie\Url\Url::fromString($value);
        $defaultTitle = $spatieUrl->getHost().' - Untitled';

        if (config('urlhub.web_title')) {
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
     * The number of shortened URLs that have been created by each User
     */
    public function numberOfUrl(): int
    {
        return self::whereUserId(auth()->id())->count();
    }

    /**
     * The total number of shortened URLs that have been created by all guest
     * users
     */
    public function numberOfUrlFromGuests(): int
    {
        return self::whereNull('user_id')->count();
    }

    /**
     * Total clicks on each shortened URLs
     *
     * @param int  $urlId  ID of the shortened URL in the URL table
     * @param bool $unique If true, only count unique clicks
     */
    public function numberOfClicks(int $urlId, bool $unique = false): int
    {
        $self = self::find($urlId);
        $total = $self->visits()->count();

        if ($unique === true) {
            $total = $self->visits()
                ->whereIsFirstClick(true)
                ->count();
        }

        return $total;
    }

    /**
     * Total clicks from the current user
     */
    public function currentUserClickCount(): int
    {
        return self::with('visits')
            ->where('user_id', auth()->id())
            ->count();
    }
}
