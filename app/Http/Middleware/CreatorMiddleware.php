<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté et a le rôle 'creator' ou 'admin'
        if (Auth::check() && (Auth::user()->role === 'creator' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        // Redirige les utilisateurs non autorisés. Vous devriez rediriger vers une route existante.
        // Par exemple, rediriger vers la page d'accueil ou une page spécifique :
        return redirect('/'); // Redirige vers la page d'accueil
        // Ou
        // return redirect()->route('nom_de_la_route'); // Redirige vers une route nommée
    }
}