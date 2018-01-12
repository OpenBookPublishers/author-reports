<?php

namespace App\Http\Controllers;

use App\User;
use App\Author;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UsersController extends Controller
{

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
     * Render an interface to manage users
     *
     * @return Response
     */
    public function index()
    {
        $users = User::orderBy('surname')->get();
        $authors = Author::orderBy('author_name')->get();
        return view('users.index', compact('users', 'authors'));
    }

    /**
     * Render an interface to edit user details
     *
     * @param int $user_id
     * @return Response
     */
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $authors = Author::orderBy('author_name')->get();
        return view('users.edit', compact('user', 'authors'));
    }

    /**
     * Update the details of a user
     *
     * @param type $user_id
     * @param Request $request
     * @return type
     */
    public function update($user_id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',
            'email' => ['email', 'required',
                Rule::unique('user')->ignore($user_id, 'user_id') ],
            'admin' => 'required',
            'display_sales' => 'required',
            'orcid' => 'nullable|min:19|max:40',
            'twitter' => 'nullable',
            'repositories' => 'nullable:'
        ]);

        $user = User::findOrFail($user_id);

        $author_found = true;
        $updated = false;

        if ($request->author === "true") {
            $author = Author::find($request->author_id);
            if (!$author) {
                $author_found = false;
            }
        } else {
            if (isset($user->author)) {
                $user->author->user_id = null;
                $user->author->save();
            }
        }

        if ($author_found) {
            $input = $request->all();
            $user->fill($input);
            $user->admin = $input['admin'] === "true" ? 1 : 0;
            $user->display_sales = $input['display_sales'] === "true" ? 1 : 0;
            $updated = $user->save();
        }

        if ($updated && $request->author === "true") {
            $author->user_id = $user->user_id;
            $author->save();
        }
        
        if ($updated && $author_found) {
            $request->session()
                    ->flash('success','Thank you. The record has been saved.');
        } elseif (!$registered && $author_found) {
            $request->session()->flash('error', 'Sorry. There was a problem.');
        } elseif (!$registered && !$author_found) {
            $request->session()->flash('error',
                'Author "' . $request->author_id . '" not found');
        }
        
        return redirect('/admin/users');
    }

    /**
     * Delete a user
     *
     * @param int $user_id
     * @return type
     */
    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);

        if (isset($user->author)) {
            $user->author->user_id = null;
            $user->author->save();
        }

        if ($user->delete()) {
            \Session::flash('success', 'The user has been deleted.');
        } else {
            \Session::flash('error', 'Sorry. There was a problem.');
        }

        return redirect('/admin/users');
    }

    /**
     * Send a create password link to the given user.
     *
     * @see https://laracasts.com/discuss/channels/laravel/get-a-password-reset-token
     * @param  int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendCreatePasswordEmail($user_id)
    {
        $user = User::findOrFail($user_id);

        $user->sendNewAccountNotification(
            app('auth.password.broker')->createToken($user)
        );

        \Session::flash('success', 'New account notification has been sent.');
        return redirect('/admin/users');
    }
}
