@extends('layouts.dashboard')

@section('title', 'Modifier Incubation')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Modifier l'Incubation</h1>
            <p class="text-gray-600 mt-1">Mise à jour des informations d'incubation</p>
        </div>

        <form action="{{ route('poultry.incubations.update', $incubation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot de volailles</label>
                    <select name="livestock_id" id="livestock_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner un lot</option>
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $incubation->livestock_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $incubation->start_date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="poultry_type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="poultry_type" id="poultry_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="POULET" {{ old('poultry_type', $incubation->poultry_type) == 'POULET' ? 'selected' : '' }}>Poulet</option>
                        <option value="CANARD" {{ old('poultry_type', $incubation->poultry_type) == 'CANARD' ? 'selected' : '' }}>Canard</option>
                        <option value="DINDE" {{ old('poultry_type', $incubation->poultry_type) == 'DINDE' ? 'selected' : '' }}>Dinde</option>
                    </select>
                </div>

                <div>
                    <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">Race</label>
                    <input type="text" name="breed" id="breed" value="{{ old('breed', $incubation->breed) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="egg_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'œufs *</label>
                    <input type="number" name="egg_count" id="egg_count" value="{{ old('egg_count', $incubation->egg_count) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">Température (°C)</label>
                    <input type="number" name="temperature" id="temperature" value="{{ old('temperature', $incubation->temperature) }}" step="0.1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="humidity" class="block text-sm font-medium text-gray-700 mb-2">Humidité (%)</label>
                    <input type="number" name="humidity" id="humidity" value="{{ old('humidity', $incubation->humidity) }}" step="0.1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="incubation_days" class="block text-sm font-medium text-gray-700 mb-2">Jours d'incubation</label>
                    <input type="number" name="incubation_days" id="incubation_days" value="{{ old('incubation_days', $incubation->incubation_days) }}" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="hatched_count" class="block text-sm font-medium text-gray-700 mb-2">Œufs éclos</label>
                    <input type="number" name="hatched_count" id="hatched_count" value="{{ old('hatched_count', $incubation->hatched_count) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="IN_PROGRESS" {{ old('status', $incubation->status) == 'IN_PROGRESS' ? 'selected' : '' }}>En cours</option>
                        <option value="COMPLETED" {{ old('status', $incubation->status) == 'COMPLETED' ? 'selected' : '' }}>Terminée</option>
                        <option value="FAILED" {{ old('status', $incubation->status) == 'FAILED' ? 'selected' : '' }}>Échouée</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes', $incubation->notes) }}</textarea>
            </div>

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
@endsection



