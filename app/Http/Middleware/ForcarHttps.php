<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redireciona pedidos HTTP simples para HTTPS (301).
 *
 * O servidor aceita ligações em HTTP sem redirecionar — visível no PageSpeed
 * Insights (categoria PWA: "Redirects HTTP traffic to HTTPS") e um risco de
 * segurança desnecessário (cookies de sessão a viajar sem encriptação).
 * Nunca se aplica em local, onde não há certificado.
 */
class ForcarHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->secure() && ! app()->environment('local')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
