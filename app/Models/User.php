<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @return array<string, string>
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
     */
    public function urls(): HasMany
    {
        return $this->hasMany(Url::class);
    }

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    /*
     * Count the number of guests (URL without user id) by IP address, then
     * grouped by IP address.
     */
    public function totalGuestUsers(): int
    {
        $url = Url::select('user_sign', DB::raw('count(*) as total'))
            ->whereNull('user_id')->groupBy('user_sign')
            ->get();

        return $url->count();
    }

    public function signature(): string
    {
        if (auth()->check() === false) {
            $dd = Helper::deviceDetector();

            return hash('sha3-256', implode([
                'ip'      => request()->ip(),
                'browser' => $dd->getClient('name'),
                'os'      => $dd->getOs('name').$dd->getOs('version'),
                'device'  => $dd->getDeviceName().$dd->getModel().$dd->getBrandName(),
                'lang'    => request()->getPreferredLanguage(),
            ]));
        }

        return (string) auth()->id();
    }
}
