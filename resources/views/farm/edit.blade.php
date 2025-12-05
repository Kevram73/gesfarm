@extends('layouts.dashboard')

@section('title', 'Modifier la ferme')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la ferme</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations de votre ferme</p>
    </div>

    <form method="POST" action="{{ route('farm.update') }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Informations générales -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tractor mr-2 text-gray-400"></i> Nom de la ferme *
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $farm->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hashtag mr-2 text-gray-400"></i> Code
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code', $farm->code) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('code')
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
                        <option value="1" {{ old('is_active', $farm->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !old('is_active', $farm->is_active) ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> Adresse
                    </label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $farm->address) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ville -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-city mr-2 text-gray-400"></i> Ville
                    </label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city', $farm->city) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pays -->
                <div>
                    <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-2 text-gray-400"></i> Pays
                    </label>
                    <select
                        id="country_id"
                        name="country_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner un pays</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $farm->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-gray-400"></i> Téléphone
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $farm->phone) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i> Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $farm->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations agricoles -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations agricoles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Superficie totale -->
                <div>
                    <label for="total_area" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler-combined mr-2 text-gray-400"></i> Superficie totale (ha)
                    </label>
                    <input
                        type="number"
                        id="total_area"
                        name="total_area"
                        value="{{ old('total_area', $farm->total_area) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('total_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Superficie cultivée -->
                <div>
                    <label for="cultivated_area" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-seedling mr-2 text-gray-400"></i> Superficie cultivée (ha)
                    </label>
                    <input
                        type="number"
                        id="cultivated_area"
                        name="cultivated_area"
                        value="{{ old('cultivated_area', $farm->cultivated_area) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('cultivated_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de sol -->
                <div>
                    <label for="soil_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-mountain mr-2 text-gray-400"></i> Type de sol
                    </label>
                    @php
                        $currentSoilType = old('soil_type', $farm->soil_type);
                        $soilTypeValues = $soilTypes->pluck('value')->toArray();
                        $isOtherSoilType = $currentSoilType && !in_array($currentSoilType, $soilTypeValues);
                    @endphp
                    <select
                        id="soil_type"
                        name="soil_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="toggleOtherInput('soil_type', 'soil_type_other')"
                    >
                        <option value="">Sélectionner un type de sol</option>
                        @foreach($soilTypes as $soilType)
                            <option value="{{ $soilType->value }}" {{ $currentSoilType == $soilType->value ? 'selected' : '' }}>
                                {{ $soilType->label }}
                            </option>
                        @endforeach
                        <option value="other" {{ $isOtherSoilType ? 'selected' : '' }}>
                            Autre
                        </option>
                    </select>
                    <div id="soil_type_other_container" class="mt-2" style="display: {{ $isOtherSoilType ? 'block' : 'none' }};">
                        <input
                            type="text"
                            id="soil_type_other"
                            name="soil_type_other"
                            value="{{ $isOtherSoilType ? $currentSoilType : old('soil_type_other') }}"
                            placeholder="Préciser le type de sol"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    @error('soil_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Climat -->
                <div>
                    <label for="climate" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-cloud-sun mr-2 text-gray-400"></i> Climat
                    </label>
                    @php
                        $currentClimate = old('climate', $farm->climate);
                        $climateValues = $climates->pluck('value')->toArray();
                        $isOtherClimate = $currentClimate && !in_array($currentClimate, $climateValues);
                    @endphp
                    <select
                        id="climate"
                        name="climate"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="toggleOtherInput('climate', 'climate_other')"
                    >
                        <option value="">Sélectionner un climat</option>
                        @foreach($climates as $climate)
                            <option value="{{ $climate->value }}" {{ $currentClimate == $climate->value ? 'selected' : '' }}>
                                {{ $climate->label }}
                            </option>
                        @endforeach
                        <option value="other" {{ $isOtherClimate ? 'selected' : '' }}>
                            Autre
                        </option>
                    </select>
                    <div id="climate_other_container" class="mt-2" style="display: {{ $isOtherClimate ? 'block' : 'none' }};">
                        <input
                            type="text"
                            id="climate_other"
                            name="climate_other"
                            value="{{ $isOtherClimate ? $currentClimate : old('climate_other') }}"
                            placeholder="Préciser le climat"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    @error('climate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Gestion -->
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Gestion</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Manager -->
                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2 text-gray-400"></i> Manager
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Aucun manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $farm->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }} ({{ ucfirst(strtolower(str_replace('_', ' ', $manager->role))) }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('farm.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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

@push('scripts')
<script>
    function toggleOtherInput(selectId, inputId) {
        const select = document.getElementById(selectId);
        const container = document.getElementById(inputId + '_container');
        const input = document.getElementById(inputId);
        
        if (select.value === 'other') {
            container.style.display = 'block';
            input.required = true;
        } else {
            container.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    }

    // Initialiser l'état au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        toggleOtherInput('soil_type', 'soil_type_other');
        toggleOtherInput('climate', 'climate_other');
    });
</script>
@endpush
@endsection

