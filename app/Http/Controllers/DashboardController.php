<?php

namespace App\Http\Controllers;

use App\BookReport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Save user information.
     *
     * @todo Remove conversion of display_sales to integer
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(Request $request)
    {
        $this->validate($request, [
            'display_sales' => 'required',
            'orcid' => 'nullable|min:20|max:40',
            'twitter' => 'nullable',
            'repositories' => 'nullable:'
        ]);
        
        $user = Auth::user();

        if ($request->display_sales) {
            // FIXME mysql lacks boolean values;
            // remove conversion below when using postgres
            $user->display_sales = $request->display_sales === "true" ? 1 : 0;
        }
        
        if ($request->orcid) {
            $user->orcid = $request->orcid;
        }
        
        if ($request->twitter) {
            $user->twitter = $request->twitter;
        }
        
        if ($request->repositories) {
            $user->repositories = $request->repositories;
        }
        
        if ($user->save()) {
            $request->session()->flash('success',
                'Thank you. Your information has been saved.');
        } else {
            $request->session()->flash('error',
                'Sorry. There was a problem.');
        }
        
        return view('dashboard');
    }

    /**
     * Show account information.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        $user = Auth::user();
        return view('account', compact('user'));
    }

    /**
     * Save account information.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'name' => 'required',
            'surname' => 'required',
            'email' => ['email', 'required',
                Rule::unique('user')->ignore($user->user_id, 'user_id') ],
            'orcid' => 'nullable|min:20|max:40',
            'twitter' => 'nullable',
        ]);

        $input = $request->all();
        $user->fill($input);

        if ($user->save()) {
            $request->session()->flash('success',
                'Thank you. Your account has been updated.');
        } else {
            $request->session()->flash('error',
                'Sorry. There was a problem.');
        }
        
        return redirect('dashboard');
    }
}
