@extends('layouts.dashboard')

@section('title', 'Modifier Poussin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier le Poussin</h1>

        <form action="{{ route('poultry.chicks.update', $chick->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot</label>
                    <select name="livestock_id" id="livestock_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner un lot</option>
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $chick->livestock_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $chick->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="hatch_date" class="block text-sm font-medium text-gray-700 mb-2">Date d'éclosion *</label>
                    <input type="date" name="hatch_date" id="hatch_date" value="{{ old('hatch_date', $chick->hatch_date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="initial_weight" class="block text-sm font-medium text-gray-700 mb-2">Poids initial (g)</label>
                        <input type="number" name="initial_weight" id="initial_weight" value="{{ old('initial_weight', $chick->initial_weight) }}" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label for="current_weight" class="block text-sm font-medium text-gray-700 mb-2">Poids actuel (g)</label>
                        <input type="number" name="current_weight" id="current_weight" value="{{ old('current_weight', $chick->current_weight) }}" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="ACTIVE" {{ old('status', $chick->status) == 'ACTIVE' ? 'selected' : '' }}>Actif</option>
                        <option value="INACTIVE" {{ old('status', $chick->status) == 'INACTIVE' ? 'selected' : '' }}>Inactif</option>
                        <option value="DECEASED" {{ old('status', $chick->status) == 'DECEASED' ? 'selected' : '' }}>Décédé</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes', $chick->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('poultry.chicks.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



