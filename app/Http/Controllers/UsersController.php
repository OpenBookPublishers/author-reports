<?php

namespace App\Http\Controllers;

use App\User;
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
        $users = User::all();
        return view('users.index', compact('users'));
    }
}
