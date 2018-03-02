<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        $data = $request->only($this->username(), 'password');
        $userNameKey = $this->getUserNameKey();
        $data[$userNameKey] = $data[$this->username()];
        unset($data[$this->username()]);

        return $data;
    }

    protected function getUserNameKey()
    {
        $email = \Request::get($this->username());

        $validation = \Validator::make([
            'email' => $email
        ], [
            'email' => 'email'
        ]);

        return $validation->fails() ? 'enrolment' : 'email';
    }

    public function username()
    {
        return 'username';
    }


}
