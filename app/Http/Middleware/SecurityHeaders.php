<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (config('app.env') === 'local') {
            return $response;
        }

        $host = $request->getHost();
        $csp = "default-src 'self' https: 'unsafe-inline' 'unsafe-eval'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://cdn.ngrok.com https://*.ngrok.io https://*.ngrok-free.app; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://api.fontshare.com https:; " .
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdn.fontshare.com https://ngrok.com https:; " .
            "img-src 'self' data: https: blob:; " .
            "connect-src 'self' https://api.stripe.com https://*.ngrok-free.app https://*.ngrok.io ws: wss: {$host}; " .
            "frame-src 'self' https://js.stripe.com https://www.youtube.com https://player.vimeo.com https://*.ngrok-free.app; " .
            "media-src 'self' blob: https:;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
