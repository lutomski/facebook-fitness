<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectIfNotFacebookConnect
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
        if (Auth::user()->isFacebookConnected()) {
            return $next($request);
        } else {
            return redirect()->action('HomeController@index')
                ->with('modal-title', 'Brak połączenia z Facebookiem.')
                ->with('modal-body', 'Aby korzystać z aplikacji połącz swoje konto z Facebookiem.');
        }
    }
}
