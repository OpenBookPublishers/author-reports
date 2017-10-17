<?php

namespace App\Http\Controllers\Auth;

use DB;
use Config;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password. Overrides trait method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(),
            $this->validationErrorMessages());
        
        // Here we will attempt to reset the user's password. If it is
        // successful we will update the password on the remote user table.
        // Otherwise we will parse the error and return the response.
        $response = $this->attemptReset($request);

        // If the password was successfully reset, we will redirect
        // the user back to the application's home authenticated view.
        // If there is an error we can redirect them back to where they
        // came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Check that the user and token are valid and perform a password reset.
     *
     * @param Request $request
     * @return string
     */
    private function attemptReset(Request $request)
    {
        if (is_null($user = User::getUserRecord($request->email))) {
            return Password::INVALID_USER;
        }

        if (! $this->checkToken($user->email, $request->token)) {
            return Password::INVALID_TOKEN;
        }

        if (! $this->resetPassword($user, $request->password)) {
            return Password::INVALID_USER;
        }

        return Password::PASSWORD_RESET;
    }

    /**
     * Check if the token and email provided match the email and hashed token
     * in the password reset table.
     *
     * @param string $email
     * @param string $inputToken
     * @return bool
     */
    private function checkToken($email, $inputToken)
    {
        $token = DB::table($this->resetTable())
                     ->where([['email', '=', $email]])
                     ->pluck('token')
                     ->first();

        return Hash::check($inputToken, $token);
    }

    /**
     * Hash the input password and trigger a password reset
     *
     * @param string $user
     * @param string $password
     * @return bool
     */
    private function resetPassword($user, $password)
    {
        // generate a random(ish) saltpass of 5 characters
        $salt = (string) substr(md5(time()), 0, 5);
        $hashed = md5($password.$salt);

        if (! User::updatePassword($user->email, $hashed, $salt)) {
            return false;
        }

        return $this->clearToken($user->email);
    }

    /**
     * Delete the reset token now that it has been used
     *
     * @param string $email
     * @return bool
     */
    private function clearToken($email)
    {
        return DB::table($this->resetTable())
                   ->where([['email', '=', $email]])
                   ->delete();     
    }

    /**
     * Get the name of password reset table specified in config/auth
     *
     * @return string
     */
    private function resetTable()
    {
        return Config::get('auth.passwords.users.table');
    }
}
