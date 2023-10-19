<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Url            $urls
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
            return hash('sha3-256', implode([
                'ip'      => request()->ip(),
                'browser' => \Browser::browserFamily(),
                'os'      => \Browser::platformFamily(),
                'device'  => \Browser::deviceFamily().\Browser::deviceModel(),
            ]));
        }

        return (string) auth()->id();
    }
}
