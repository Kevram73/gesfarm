@extends('layouts.dashboard')

@section('title', 'Modifier le champ')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier le champ</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations du champ</p>
    </div>

    <form method="POST" action="{{ route('fields.update', $field->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map mr-2 text-gray-400"></i> Nom du champ *
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $field->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Superficie -->
                <div>
                    <label for="area" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler-combined mr-2 text-gray-400"></i> Superficie (ha) *
                    </label>
                    <input
                        type="number"
                        id="area"
                        name="area"
                        value="{{ old('area', $field->area) }}"
                        required
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on mr-2 text-gray-400"></i> Statut
                    </label>
                    <select
                        id="is_active"
                        name="is_active"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="1" {{ old('is_active', $field->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $field->is_active) ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Caractéristiques du sol -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Caractéristiques du sol</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type de sol -->
                <div>
                    <label for="soil_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-mountain mr-2 text-gray-400"></i> Type de sol
                    </label>
                    <select
                        id="soil_type"
                        name="soil_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner un type de sol</option>
                        @foreach($soilTypes as $soilType)
                            <option value="{{ $soilType->value }}" {{ old('soil_type', $field->soil_type) == $soilType->value ? 'selected' : '' }}>
                                {{ $soilType->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- pH -->
                <div>
                    <label for="ph_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-flask mr-2 text-gray-400"></i> Niveau de pH (0-14)
                    </label>
                    <input
                        type="number"
                        id="ph_level"
                        name="ph_level"
                        value="{{ old('ph_level', $field->ph_level) }}"
                        step="0.1"
                        min="0"
                        max="14"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Fertilité -->
                <div>
                    <label for="fertility" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-seedling mr-2 text-gray-400"></i> Fertilité
                    </label>
                    <select
                        id="fertility"
                        name="fertility"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner</option>
                        @foreach($fertilityOptions as $fertility)
                            <option value="{{ $fertility->value }}" {{ old('fertility', $field->fertility) == $fertility->value ? 'selected' : '' }}>
                                {{ $fertility->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Irrigation -->
                <div>
                    <label for="irrigation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tint mr-2 text-gray-400"></i> Irrigation
                    </label>
                    <select
                        id="irrigation"
                        name="irrigation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner</option>
                        @foreach($irrigationOptions as $irrigation)
                            <option value="{{ $irrigation->value }}" {{ old('irrigation', $field->irrigation) == $irrigation->value ? 'selected' : '' }}>
                                {{ $irrigation->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Localisation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Localisation -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> Localisation
                    </label>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="{{ old('location', $field->location) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >{{ old('notes', $field->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('fields.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button
                type="submit"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold"
            >
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection



