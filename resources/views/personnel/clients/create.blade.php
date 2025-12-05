@extends('layouts.dashboard')

@section('title', 'Nouveau client')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nouveau client</h1>
        <p class="text-gray-600 mt-1">Ajoutez un nouveau client</p>
    </div>

    <form method="POST" action="{{ route('customers.store') }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i> Nom *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i> Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-gray-400"></i> Téléphone
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i> Type
                    </label>
                    <select id="type" name="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        <option value="INDIVIDUAL" {{ old('type') == 'INDIVIDUAL' ? 'selected' : '' }}>Individuel</option>
                        <option value="BUSINESS" {{ old('type') == 'BUSINESS' ? 'selected' : '' }}>Entreprise</option>
                        <option value="COOPERATIVE" {{ old('type') == 'COOPERATIVE' ? 'selected' : '' }}>Coopérative</option>
                    </select>
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on mr-2 text-gray-400"></i> Statut
                    </label>
                    <select id="is_active" name="is_active"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="1" {{ old('is_active', true) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ !old('is_active', true) ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> Adresse
                    </label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('customers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-save mr-2"></i> Créer le client
            </button>
        </div>
    </form>
</div>
@endsection
