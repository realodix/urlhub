<?php

namespace App\Providers;

use App\Actions\Fortify\{CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation};
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, RateLimiter};
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authenticate();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->identity;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    /**
     * @return void
     */
    private function authenticate()
    {
        Fortify::loginView(function () {
            return view('frontend.auth.login');
        });

        Fortify::requestPasswordResetLinkView(function (Request $request, $token = null) {
            return view('frontend.auth.passwords.reset')->with(
                [
                    'token' => $token,
                    'email' => $request->email,
                ]
            );
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->identity)
                ->orWhere('name', $request->identity)
                ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

    }
}
