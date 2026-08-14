<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fecha o site atrás de uma password quando STAGING_PASSWORD está definida.
 *
 * Serve para ambientes de pré-produção: impede que o trabalho em curso fique
 * visível na internet e que os motores de busca o indexem (conteúdo duplicado
 * face ao site real). Em produção basta não definir a variável.
 */
class ProtegerStaging
{
    public function handle(Request $request, Closure $next): Response
    {
        $password = config('staging.password');

        if (blank($password)) {
            return $next($request);
        }

        // O endpoint de verificação de saúde tem de continuar acessível.
        if ($request->is('up')) {
            return $next($request);
        }

        $utilizador = config('staging.username');

        if (! hash_equals((string) $utilizador, (string) $request->getUser())
            || ! hash_equals((string) $password, (string) $request->getPassword())) {
            return response('Acesso restrito.', Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => 'Basic realm="GOCARMAT — ambiente de pré-produção"',
                'X-Robots-Tag' => 'noindex, nofollow',
            ]);
        }

        $resposta = $next($request);

        // Mesmo autenticado, nunca deve ser indexado.
        $resposta->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $resposta;
    }
}
