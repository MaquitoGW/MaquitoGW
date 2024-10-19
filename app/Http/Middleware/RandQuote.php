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
        // Recupera as frases do arquivo de tradução
        $frases = __('quotes');

        // Pega um índice aleatório
        $indiceAleatorio = array_rand($frases);

        // Pega a frase e o autor correspondentes
        $fraseAleatoria = $frases[$indiceAleatorio]['quote'] ?? 'Frase não encontrada';
        $autorAleatorio = $frases[$indiceAleatorio]['author'] ?? 'Autor desconhecido';

        // Compartilha a frase com todas as views
        view()->share('quote', $fraseAleatoria);
        view()->share('author', $autorAleatorio);

        // Continua o ciclo da requisição
        return $next($request);
    }
}
