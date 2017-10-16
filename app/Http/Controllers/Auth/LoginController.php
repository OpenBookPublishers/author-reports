<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function attemptLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $user = User::getUserRecord($email);

        if ($user === null) {
            return false;
        }

        return $user->password === md5($password.$user->saltpass);
    }

    /**
     * The user has been authenticated. If there is a match to a local user,
     * update its details; otherwise, create a local account. After redirect
     * to home page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $dbUser = User::getUserRecord($request->email);

        // propert match: the two accounts are perfectly matched
        $userObj = User::where('user_id', '=', $dbUser->user_id)->first();

        // probably a first login: accounts are not linked, but email matches
        if ($userObj === null) {
            $userObj = User::where('email', '=', $dbUser->email)->first();
        }

        // this user will not have any permissions
        if ($userObj === null) {
            $userObj = New User;
        }

        $userObj->name = $dbUser->forename;
        $userObj->surname = $dbUser->surname;
        $userObj->email = $dbUser->email;
        $userObj->password = bcrypt($request->password);
        $newAccount = $dbUser->user_id === null;
        $userObj->save();

        if ($newAccount) {
            User::linkAccounts($dbUser->customerID, $userObj->user_id);
        }

        Auth::login($userObj);
        
        return redirect()->intended($this->redirectPath());
    }
}
