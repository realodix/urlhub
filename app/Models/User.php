<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property bool $forward_query
 * @property string $timezone
 * @property string $two_factor_secret
 * @property string $two_factor_recovery_codes
 * @property string $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Url> $urls
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /** @var null */
    const GUEST_ID = null;

    /** @var string */
    const GUEST_NAME = 'guest';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password',
        'forward_query', 'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'forward_query' => 'boolean',
        ];
    }

    /**
     * Find a user ID by name, or return the GUEST_ID for guests.
     *
     * @param string $name The name of the user or 'guests'.
     * @return int|null The user ID or null for guests.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findIdByName(string $name): ?int
    {
        if ($name === self::GUEST_NAME) {
            return self::GUEST_ID;
        }

        return self::where('name', $name)->firstOrFail()->id;
    }

    /**
     * Get the short URLs created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Url, $this>
     */
    public function urls()
    {
        return $this->hasMany(Url::class);
    }

    /**
     * Get all of the visits for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function visits()
    {
        return $this->hasManyThrough(Visit::class, Url::class);
    }

    /**
     * Get the user's timezone. If the attribute is null, it defaults to the
     * application's timezone.
     */
    protected function timezone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? config('app.timezone'),
        );
    }
}
