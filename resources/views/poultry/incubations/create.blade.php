@extends('layouts.dashboard')

@section('title', 'Nouvelle Incubation')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Nouvelle Incubation</h1>
            <p class="text-gray-600 mt-1">Enregistrez une nouvelle incubation d'œufs</p>
        </div>

        <form action="{{ route('poultry.incubations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Lot de volailles -->
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot de volailles</label>
                    <select name="livestock_id" id="livestock_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner un lot (optionnel)</option>
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->type }})
                            </option>
                        @endforeach
                    </select>
                    @error('livestock_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de début -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de volaille -->
                <div>
                    <label for="poultry_type" class="block text-sm font-medium text-gray-700 mb-2">Type de volaille *</label>
                    <select name="poultry_type" id="poultry_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner un type</option>
                        <option value="POULET" {{ old('poultry_type') == 'POULET' ? 'selected' : '' }}>Poulet</option>
                        <option value="CANARD" {{ old('poultry_type') == 'CANARD' ? 'selected' : '' }}>Canard</option>
                        <option value="DINDE" {{ old('poultry_type') == 'DINDE' ? 'selected' : '' }}>Dinde</option>
                        <option value="AUTRE" {{ old('poultry_type') == 'AUTRE' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('poultry_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Race -->
                <div>
                    <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">Race</label>
                    <input type="text" name="breed" id="breed" value="{{ old('breed') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('breed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre d'œufs -->
                <div>
                    <label for="egg_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'œufs *</label>
                    <input type="number" name="egg_count" id="egg_count" value="{{ old('egg_count') }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('egg_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Taille des œufs -->
                <div>
                    <label for="egg_size" class="block text-sm font-medium text-gray-700 mb-2">Taille des œufs</label>
                    <select name="egg_size" id="egg_size"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner</option>
                        <option value="PETIT" {{ old('egg_size') == 'PETIT' ? 'selected' : '' }}>Petit</option>
                        <option value="MOYEN" {{ old('egg_size') == 'MOYEN' ? 'selected' : '' }}>Moyen</option>
                        <option value="GRAND" {{ old('egg_size') == 'GRAND' ? 'selected' : '' }}>Grand</option>
                        <option value="EXTRA_GRAND" {{ old('egg_size') == 'EXTRA_GRAND' ? 'selected' : '' }}>Extra grand</option>
                    </select>
                    @error('egg_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Température -->
                <div>
                    <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">
                        Température (°C)
                        <span class="text-xs text-gray-500" id="temp_recommendation"></span>
                    </label>
                    <input type="number" name="temperature" id="temperature" value="{{ old('temperature') }}" step="0.1" min="0" max="50"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('temperature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Humidité -->
                <div>
                    <label for="humidity" class="block text-sm font-medium text-gray-700 mb-2">
                        Humidité (%)
                        <span class="text-xs text-gray-500" id="humidity_recommendation"></span>
                    </label>
                    <input type="number" name="humidity" id="humidity" value="{{ old('humidity') }}" step="0.1" min="0" max="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('humidity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jours d'incubation -->
                <div>
                    <label for="incubation_days" class="block text-sm font-medium text-gray-700 mb-2">
                        Jours d'incubation
                        <span class="text-xs text-gray-500" id="days_recommendation"></span>
                    </label>
                    <input type="number" name="incubation_days" id="incubation_days" value="{{ old('incubation_days') }}" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('incubation_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('poultry.incubations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('poultry_type').addEventListener('change', function() {
    const type = this.value;
    // Ici, vous pouvez charger les recommandations depuis l'API si nécessaire
    // Pour l'instant, valeurs par défaut
    if (type === 'POULET') {
        document.getElementById('temp_recommendation').textContent = '(Recommandé: 37.5°C)';
        document.getElementById('humidity_recommendation').textContent = '(Recommandé: 55%)';
        document.getElementById('days_recommendation').textContent = '(Recommandé: 21 jours)';
    } else if (type === 'CANARD') {
        document.getElementById('temp_recommendation').textContent = '(Recommandé: 37.5°C)';
        document.getElementById('humidity_recommendation').textContent = '(Recommandé: 55%)';
        document.getElementById('days_recommendation').textContent = '(Recommandé: 28 jours)';
    } else if (type === 'DINDE') {
        document.getElementById('temp_recommendation').textContent = '(Recommandé: 37.5°C)';
        document.getElementById('humidity_recommendation').textContent = '(Recommandé: 55%)';
        document.getElementById('days_recommendation').textContent = '(Recommandé: 28 jours)';
    } else {
        document.getElementById('temp_recommendation').textContent = '';
        document.getElementById('humidity_recommendation').textContent = '';
        document.getElementById('days_recommendation').textContent = '';
    }
});
</script>
@endpush
@endsection



