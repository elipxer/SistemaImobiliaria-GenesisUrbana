<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserUniqueAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->token_access != session()->get('access_token')) {
            // Faz o logout do usuário
            Auth::logout();
     
            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()->route('login');
        }
     
        
        return $next($request);
    }
}
