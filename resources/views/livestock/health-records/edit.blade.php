@extends('layouts.dashboard')

@section('title', 'Modifier Fiche Sanitaire')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la Fiche Sanitaire</h1>

        <form action="{{ route('livestock.health-records.update', $healthRecord->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Animal *</label>
                    <select name="livestock_id" id="livestock_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $healthRecord->livestock_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $healthRecord->date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" id="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="VACCINATION" {{ old('type', $healthRecord->type) == 'VACCINATION' ? 'selected' : '' }}>Vaccination</option>
                        <option value="TREATMENT" {{ old('type', $healthRecord->type) == 'TREATMENT' ? 'selected' : '' }}>Traitement</option>
                        <option value="CHECKUP" {{ old('type', $healthRecord->type) == 'CHECKUP' ? 'selected' : '' }}>Contrôle</option>
                        <option value="SURGERY" {{ old('type', $healthRecord->type) == 'SURGERY' ? 'selected' : '' }}>Chirurgie</option>
                        <option value="OTHER" {{ old('type', $healthRecord->type) == 'OTHER' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $healthRecord->description) }}</textarea>
                </div>

                <div>
                    <label for="veterinarian" class="block text-sm font-medium text-gray-700 mb-2">Vétérinaire</label>
                    <input type="text" name="veterinarian" id="veterinarian" value="{{ old('veterinarian', $healthRecord->veterinarian) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Coût (FCFA)</label>
                    <input type="number" name="cost" id="cost" value="{{ old('cost', $healthRecord->cost) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes', $healthRecord->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('livestock.health-records.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



