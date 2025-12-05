@extends('layouts.dashboard')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
        <p class="text-gray-600 mt-1">Gérez vos informations personnelles</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- En-tête du profil -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-green-600 text-3xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-green-100">{{ $user->email }}</p>
                    <p class="text-green-100 text-sm mt-1">
                        {{ ucfirst(strtolower(str_replace('_', ' ', $user->role))) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire de profil -->
        <form method="POST" action="{{ route('home.profile.update') }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i> Nom *
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Nom complet -->
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-gray-400"></i> Nom complet
                        </label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            value="{{ old('fullname', $user->fullname) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i> Adresse email *
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-gray-400"></i> Téléphone
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> Adresse
                        </label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address', $user->address) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le mot de passe</h3>
                    <p class="text-sm text-gray-600 mb-4">Laissez vide si vous ne souhaitez pas changer le mot de passe</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i> Nouveau mot de passe
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Minimum 8 caractères"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i> Confirmer le mot de passe
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Confirmer le mot de passe"
                            >
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations supplémentaires</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                            <input
                                type="text"
                                value="{{ ucfirst(strtolower(str_replace('_', ' ', $user->role))) }}"
                                disabled
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ferme</label>
                            <input
                                type="text"
                                value="{{ \App\Helpers\FarmHelper::getFarm()->name }}"
                                disabled
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                            >
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('home.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold"
                    >
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

