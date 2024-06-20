<?php

namespace Pearls\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Pearls\User\Providers\UserRouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = \Pearls\User\Providers\UserRouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    protected function authenticated(Request $request, $user)
    {        
        if ($user->status != 'active') {
            Auth::logout();
            
            flash('Please activate your account before logging in.')->error();

            return redirect('login');
        }
    }

    public function username()
    {
        return 'username';
    }

}
