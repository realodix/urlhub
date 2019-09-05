<?php

namespace UrlHub\UserManagement\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use UrlHub\UserManagement\Repository\Contracts\UserRepositoryInterface;
use UrlHub\UserManagement\Repository\Contracts\RoleRepositoryInterface;
use UrlHub\UserManagement\Http\Requests\Auth\UserLogin;
use UrlHub\UserManagement\Http\Requests\Auth\UserRegistration;
use Auth;

class AuthController extends Controller
{
    protected $roleRepository;
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $user,
        RoleRepositoryInterface $role
    ) {
        $this->userRepository = $user;
        $this->roleRepository = $role;
    }

    public function loginForm()
    {
        return view('user-management.auth.login');
    }

    public function registerForm()
    {
        return view('user-management.auth.register');
    }

    public function login(UserLogin $request)
    {
        $username    = config('laravel_user_management.auth.username');
        $credentials = [$username => $request->{$username}, 'password' => $request->password, 'status' => 'accepted'];

        if (\Auth::attempt($credentials)) {
            $user = \Auth::user();
            return redirect()->intended('/');
        }

        $user = $this->userRepository->findBy(["$username" => $request->{$username}]);
        if ($user && $user->status != 'accepted') {
            return redirect()->back()->with('message', [
                'type'  => 'danger',
                'text'  => trans('trans.your_account_does_not_activated')
            ]);
        }

        return redirect()->back()->with('message', [
            'type'  => 'danger',
            'text'  => trans('trans.username_or_password_wrong')
        ]);
    }

    public function register(UserRegistration $request)
    {
        $userDefaultRole = $this->roleRepository->findBy([
            'name'  => config('laravel_user_management.auth.user_default_role')
        ]);

        if (!$userDefaultRole) {
            return redirect()->back()->with('message', [
                'type'  => 'danger',
                'text'  => trans('trans.default_role_does_not_exist'),
            ]);
        }

        //// FOR ACTIVE ACCOUNT BASE PROJECT CONFIG ONE OF THE FIELDS [MOBILE, EMAIL] SHOULD BE REQUIRED
        $user = $this->userRepository->store([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $request->password,
            'mobile'        => $request->mobile,
            'status'        => config('laravel_user_management.auth.default_user_status'),
        ]);

        /// ASSIGN DEFAULT ROLE TO USER
        $this->roleRepository->setRoleToMember($user, $userDefaultRole);

        \Auth::login($user);

        return redirect()->route(config('laravel_user_management.auth.dashboard_route_name_user_redirection'))
        ->with('message', [
            'type'  => 'success',
            'text'  => trans('trans.account_created_successfully')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
