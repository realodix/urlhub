<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int            $id
 * @property string         $name
 * @property string         $email
 * @property string         $email_verified_at
 * @property string         $password
 * @property string         $two_factor_secret
 * @property string         $two_factor_recovery_codes
 * @property string         $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property Url            $urls
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use \Spatie\Permission\Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{email_verified_at: 'datetime', password: 'hashed'}
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent: Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the urls associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urls()
    {
        return $this->hasMany(Url::class);
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    /*
     * Count the total number of guest users
     */
    public function totalGuestUsers(): int
    {
        return Url::where('user_id', null)
            ->distinct('user_sign')
            ->count();
    }

    public function signature(): string
    {
        if (auth()->check() === false) {
            $device = Helper::deviceDetector();
            $browser = $device->getClient();
            $os = $device->getOs();

            $userDeviceInfo = implode([
                request()->ip(),
                isset($browser['name']) ? $browser['name'] : '',
                isset($os['name']) ? $os['name'] : '',
                isset($os['version']) ? $os['version'] : '',
                $device->getDeviceName().$device->getModel().$device->getBrandName(),
                request()->getPreferredLanguage(),
            ]);

            return hash('xxh3', $userDeviceInfo);
        }

        return (string) auth()->id();
    }
}
