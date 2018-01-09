<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
            'admin' => 'required',
            'author' => 'required',
            'author_id' => 'required_if:author,==,true'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'admin' => $data['admin'] === "true" ? 1 : 0
        ]);
    }

    /**
     * Handle a registration request for the application.
     * Overrides parent method.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $author_found = true;
        $registered = false;

        if ($request->author === "true") {
            $author = Author::find($request->author_id);
            if (!$author) {
                $author_found = false;
            }
        }

        if ($author_found) {
            event(new Registered($user = $this->create($request->all())));
            $registered = true;
        }

        if ($registered && $request->author === "true") {
            $author->user_id = $user->user_id;
            $author->save();
        }

        if ($registered && $author_found) {
             \Session::flash('success', 'User created.');
        } elseif (!$registered && $author_found) {
             \Session::flash('error', 'User could not be created.');
        } elseif (!$registered && !$author_found) {
            \Session::flash('error',
                    'Author "' . $request->author_id . '" not found');
        }

        return redirect($this->redirectPath());
    }
}
