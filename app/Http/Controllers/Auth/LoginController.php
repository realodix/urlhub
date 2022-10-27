<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Check either username or email.
     *
     * @psalm-suppress PossiblyNullReference get(), merge()
     * @psalm-suppress PossiblyInvalidMethodCall get(), merge()
     *
     * @return string
     */
    public function username()
    {
        $identity = request()->get('identity');
        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        request()->merge([$fieldName => $identity]);

        return $fieldName;
    }

    // /**
    //  * After login redirect back to previous page.
    //  *
    //  * @return string
    //  */
    // public function redirectTo()
    // {
    //     if ($this->request->has('previous')) {
    //         $this->redirectTo = $this->request->get('previous');
    //     }
    //
    //     return $this->redirectTo ?? '/admin';
    // }

    /**
     * Get the failed login response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $request->session()->put('login_error', trans('auth.failed'));

        throw ValidationException::withMessages([
            'error' => [trans('auth.failed')],
        ]);
    }
}
