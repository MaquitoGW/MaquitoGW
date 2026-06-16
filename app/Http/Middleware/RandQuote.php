<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RandQuote
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $frases = __('quotes');

        if (!is_array($frases) || empty($frases)) {
            view()->share('quote', 'Frase não encontrada');
            view()->share('author', 'Autor desconhecido');
            return $next($request);
        }

        $indiceAleatorio = array_rand($frases);

        $fraseAleatoria = $frases[$indiceAleatorio]['quote'] ?? 'Frase não encontrada';
        $autorAleatorio = $frases[$indiceAleatorio]['author'] ?? 'Autor desconhecido';

        view()->share('quote', $fraseAleatoria);
        view()->share('author', $autorAleatorio);

        return $next($request);
    }
}
