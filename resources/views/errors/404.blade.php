<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <div class="mb-8">
                <div class="inline-block p-6 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-6xl"></i>
                </div>
                <h1 class="text-6xl font-bold text-gray-900 mb-2">404</h1>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page non trouvée</h2>
                <p class="text-gray-600 mb-8">
                    Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                </p>
            </div>
            
            <div class="space-y-4">
                <a href="{{ url('/home') }}" class="inline-block w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    <i class="fas fa-home mr-2"></i> Retour à l'accueil
                </a>
                <button onclick="window.history.back()" class="inline-block w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Page précédente
                </button>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur.
                </p>
            </div>
        </div>
    </div>
</body>
</html>



