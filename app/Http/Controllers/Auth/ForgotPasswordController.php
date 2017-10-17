<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user. Overrides method in trait.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user.
        // Once we have attempted to send the link, we will examine
        // the response then see the message we need to show to the user.
        // Finally, we'll send out a proper response.
        $response = $this->sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function sendResetLink($email)
    {
        // check if correct email address
        $dbUser = User::getUserRecord($email);

        if (is_null($dbUser)) {
            return Password::INVALID_USER;
        }

        $user = New User;
        $user->email = $dbUser->email;

        $user->sendPasswordResetNotification(
            $this->broker()->createToken($user)
        );

        return Password::RESET_LINK_SENT;
    }
}
