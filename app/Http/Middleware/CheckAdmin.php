<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAdmin = Auth::guest() ? false : (Auth::user()->isAdmin());

        if (!$isAdmin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $request->session()->flash('error',
                    'Sorry. You do not have access to this page.');
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}
