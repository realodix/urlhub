<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
     * Count the number of guests (URL without user id) by user_sign, then
     * grouped by user_sign.
     */
    public function totalGuestUsers(): int
    {
        $url = DB::table('urls')
            ->select('user_sign')
            ->where('user_id', null)
            ->groupBy('user_sign')
            ->get();

        return $url->count();
    }

    public function signature(): string
    {
        if (auth()->check() === false) {
            $device = Helper::deviceDetector();

            return hash('xxh3', implode([
                'ip'      => request()->ip(),
                'browser' => $device->getClient('name'),
                'os'      => $device->getOs('name').$device->getOs('version'),
                'device'  => $device->getDeviceName().$device->getModel().$device->getBrandName(),
                'lang'    => request()->getPreferredLanguage(),
            ]));
        }

        return (string) auth()->id();
    }
}
