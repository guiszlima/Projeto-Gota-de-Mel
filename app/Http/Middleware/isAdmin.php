<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        // Verifica se o campo is_pending é false
       
        if ($user->role_id === 3){
         return $next($request);
        }
        return redirect()->route('menu');
        // Se is_pending for true, passa a requisição para o próximo middleware/controlador
       
    }
        
    }