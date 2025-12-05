@extends('layouts.dashboard')

@section('title', 'Nouveau Vêlage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouveau Vêlage</h1>

        <form action="{{ route('livestock.calvings.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="mother_id" class="block text-sm font-medium text-gray-700 mb-2">Mère *</label>
                    <select name="mother_id" id="mother_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner une mère</option>
                        @foreach($mothers as $mother)
                            <option value="{{ $mother->id }}">{{ $mother->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="breeding_id" class="block text-sm font-medium text-gray-700 mb-2">Saillie (optionnel)</label>
                    <select name="breeding_id" id="breeding_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner une saillie</option>
                        @foreach($breedings as $breeding)
                            <option value="{{ $breeding->id }}" {{ old('breeding_id', $selectedBreeding?->id) == $breeding->id ? 'selected' : '' }}>
                                {{ $breeding->male->name ?? 'N/A' }} x {{ $breeding->female->name ?? 'N/A' }} - {{ $breeding->date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="NORMAL" {{ old('type') == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                        <option value="DIFFICULT" {{ old('type') == 'DIFFICULT' ? 'selected' : '' }}>Difficile</option>
                        <option value="CAESAREAN" {{ old('type') == 'CAESAREAN' ? 'selected' : '' }}>Césarienne</option>
                    </select>
                </div>

                <div>
                    <label for="offspring_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre de petits nés *</label>
                    <input type="number" name="offspring_count" id="offspring_count" value="{{ old('offspring_count', 1) }}" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="complications" class="block text-sm font-medium text-gray-700 mb-2">Complications</label>
                    <input type="text" name="complications" id="complications" value="{{ old('complications') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('livestock.calvings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



