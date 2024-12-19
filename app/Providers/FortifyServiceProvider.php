<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
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
     *
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->loginAndRegister();
        $this->register();
        $this->password();

        // Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        // Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        // Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->identity;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    /**
     * @return void
     */
    private function loginAndRegister()
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->identity)
                ->orWhere('name', $request->identity)
                ->first();

            if (($user !== null) && Hash::check($request->password, $user->password)) {
                return $user;
            }

            $request->session()->put('login_error', trans('auth.failed'));

            throw ValidationException::withMessages([
                'error' => [trans('auth.failed')],
            ]);
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * @return void
     */
    private function password()
    {
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // @codeCoverageIgnoreStart
        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', [
                'token' => $request->route('token'),
                'email' => $request->email,
            ]);
        });
        // @codeCoverageIgnoreEnd

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::confirmPasswordsUsing(function (User $user) {
            return Hash::check(request('password'), $user->password);
        });
    }
}
