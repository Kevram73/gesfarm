<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VercelCorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Liste des origines autorisées
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:3001',
            'http://127.0.0.1:3000',
            'http://127.0.0.1:3001',
            'https://your-frontend-app.vercel.app', // Remplacez par votre URL frontend
            'https://gesfarm-frontend.vercel.app',  // URL suggérée pour le frontend
        ];

        // Ajouter l'origine de la requête si elle est dans la liste autorisée
        $origin = $request->headers->get('Origin');
        if (in_array($origin, $allowedOrigins)) {
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        } else {
            $response = $next($request);
            // Pour les requêtes sans origine (comme les requêtes directes), autoriser toutes les origines
            if (!$origin) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            }
        }

        // Headers CORS standard
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application, X-CSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '86400');

        // Gérer les requêtes preflight OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(200);
            return $response;
        }

        return $response;
    }
}
