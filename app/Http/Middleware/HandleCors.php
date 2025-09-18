<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $this->getAllowedOrigin($request);
        
        // Gérer les requêtes preflight OPTIONS
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Access-Control-Request-Method, Access-Control-Request-Headers')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400')
                ->header('Vary', 'Origin');
        }

        $response = $next($request);

        // Ajouter les headers CORS à la réponse seulement si l'origine est autorisée
        if ($origin) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Access-Control-Request-Method, Access-Control-Request-Headers');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Expose-Headers', 'Cache-Control, Content-Language, Content-Type, Expires, Last-Modified, Pragma');
            $response->headers->set('Vary', 'Origin');
        }

        return $response;
    }

    /**
     * Déterminer l'origine autorisée pour la requête
     */
    private function getAllowedOrigin(Request $request): string
    {
        $origin = $request->headers->get('Origin');
        
        // Origines autorisées pour le développement
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:3001',
            'http://127.0.0.1:3000',
            'http://127.0.0.1:3001',
            'http://62.171.181.213:3000',
            'https://62.171.181.213:3000',
            'http://62.171.181.213',
            'https://62.171.181.213',
        ];

        // Ajouter les origines de production si configurées
        if (config('app.env') === 'production') {
            $allowedOrigins = array_merge($allowedOrigins, [
                // Ajoutez vos domaines de production ici
                // 'https://votre-domaine.com',
                // 'https://www.votre-domaine.com',
            ]);
        }

        // Vérifier si l'origine est autorisée
        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }

        // Pour le développement, autoriser localhost et l'IP serveur avec ou sans port
        if (config('app.env') === 'local' && $origin && (
            preg_match('/^https?:\/\/localhost:\d+$/', $origin) ||
            preg_match('/^https?:\/\/62\.171\.181\.213(:\d+)?$/', $origin)
        )) {
            return $origin;
        }

        // Par défaut, ne pas autoriser d'origine
        return '';
    }
}
