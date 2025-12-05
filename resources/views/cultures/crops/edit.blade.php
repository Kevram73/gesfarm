@extends('layouts.dashboard')

@section('title', 'Modifier la culture')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la culture</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations de la culture</p>
    </div>

    <form method="POST" action="{{ route('crops.update', $crop->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-seedling mr-2 text-gray-400"></i> Nom de la culture *
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $crop->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i> Type
                    </label>
                    <select
                        id="type"
                        name="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner un type</option>
                        @foreach($cropTypes as $type)
                            <option value="{{ $type->value }}" {{ old('type', $crop->type) == $type->value ? 'selected' : '' }}>
                                {{ $type->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-gray-400"></i> Catégorie
                    </label>
                    <select
                        id="category"
                        name="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($cropCategories as $category)
                            <option value="{{ $category->value }}" {{ old('category', $crop->category) == $category->value ? 'selected' : '' }}>
                                {{ $category->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Variété -->
                <div>
                    <label for="variety" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dna mr-2 text-gray-400"></i> Variété
                    </label>
                    <input
                        type="text"
                        id="variety"
                        name="variety"
                        value="{{ old('variety', $crop->variety) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
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
                        <option value="1" {{ old('is_active', $crop->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $crop->is_active) ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informations détaillées -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations détaillées</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-400"></i> Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >{{ old('description', $crop->description) }}</textarea>
                </div>

                <!-- Saison de plantation -->
                <div>
                    <label for="planting_season" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i> Saison de plantation
                    </label>
                    <input
                        type="text"
                        id="planting_season"
                        name="planting_season"
                        value="{{ old('planting_season', $crop->planting_season) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Saison de récolte -->
                <div>
                    <label for="harvest_season" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-gray-400"></i> Saison de récolte
                    </label>
                    <input
                        type="text"
                        id="harvest_season"
                        name="harvest_season"
                        value="{{ old('harvest_season', $crop->harvest_season) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Période de croissance -->
                <div>
                    <label for="growth_period" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-gray-400"></i> Période de croissance (jours)
                    </label>
                    <input
                        type="number"
                        id="growth_period"
                        name="growth_period"
                        value="{{ old('growth_period', $crop->growth_period) }}"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Besoins en eau -->
                <div>
                    <label for="water_needs" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tint mr-2 text-gray-400"></i> Besoins en eau
                    </label>
                    <input
                        type="text"
                        id="water_needs"
                        name="water_needs"
                        value="{{ old('water_needs', $crop->water_needs) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Exigences du sol -->
                <div>
                    <label for="soil_requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-mountain mr-2 text-gray-400"></i> Exigences du sol
                    </label>
                    <input
                        type="text"
                        id="soil_requirements"
                        name="soil_requirements"
                        value="{{ old('soil_requirements', $crop->soil_requirements) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Prix -->
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Prix</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Prix par unité -->
                <div>
                    <label for="price_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i> Prix par unité (FCFA)
                    </label>
                    <input
                        type="number"
                        id="price_per_unit"
                        name="price_per_unit"
                        value="{{ old('price_per_unit', $crop->price_per_unit) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                <!-- Unité -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-gray-400"></i> Unité
                    </label>
                    <input
                        type="text"
                        id="unit"
                        name="unit"
                        value="{{ old('unit', $crop->unit) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('crops.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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



