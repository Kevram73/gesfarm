@extends('layouts.dashboard')

@section('title', 'Modifier Production')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la Production</h1>

        <form action="{{ route('poultry.egg-production.update', $production->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot *</label>
                    <select name="livestock_id" id="livestock_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $production->livestock_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $production->date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="egg_count" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'œufs *</label>
                    <input type="number" name="egg_count" id="egg_count" value="{{ old('egg_count', $production->egg_count) }}" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="egg_weight" class="block text-sm font-medium text-gray-700 mb-2">Poids moyen (g)</label>
                    <input type="number" name="egg_weight" id="egg_weight" value="{{ old('egg_weight', $production->egg_weight) }}" step="0.1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes', $production->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('poultry.egg-production.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



