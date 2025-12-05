@extends('layouts.auth')

@section('title', 'Mot de passe oublié - Farm Manager')

@section('content')
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo et titre -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-full mb-4">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Mot de passe oublié</h1>
                <p class="text-gray-600 mt-2">Entrez votre email pour recevoir un lien de réinitialisation</p>
            </div>

            <!-- Messages -->
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i> Adresse email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            placeholder="votre@email.com"
                        >
                    </div>

                    <!-- Bouton -->
                    <button
                        type="submit"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                    >
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer le lien de réinitialisation
                    </button>
                </div>
            </form>

            <!-- Liens -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-green-600 hover:text-green-700 font-semibold block">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>
@endsection

