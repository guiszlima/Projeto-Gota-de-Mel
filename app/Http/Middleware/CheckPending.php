<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPending
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
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            // Verifica se o campo is_pending é false
            if ($user->is_pending == true) {
                // Redireciona para a rota 'autorizado'
                return redirect()->route('register');
            }

            // Se is_pending for true, passa a requisição para o próximo middleware/controlador
            return $next($request);
        }

        // Se o usuário não estiver autenticado, redireciona para a rota de registro
        return redirect()->route('login')->with('error', 'Você precisa estar autenticado.');
    }
}