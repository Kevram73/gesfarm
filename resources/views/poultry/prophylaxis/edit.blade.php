@extends('layouts.dashboard')

@section('title', 'Modifier Prophylaxie')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la Prophylaxie</h1>

        <form action="{{ route('poultry.prophylaxis.update', $prophylaxis->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot *</label>
                    <select name="livestock_id" id="livestock_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $prophylaxis->livestock_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $prophylaxis->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $prophylaxis->start_date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) *</label>
                        <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $prophylaxis->duration_days) }}" min="1" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="IN_PROGRESS" {{ old('status', $prophylaxis->status) == 'IN_PROGRESS' ? 'selected' : '' }}>En cours</option>
                        <option value="COMPLETED" {{ old('status', $prophylaxis->status) == 'COMPLETED' ? 'selected' : '' }}>Terminée</option>
                        <option value="CANCELLED" {{ old('status', $prophylaxis->status) == 'CANCELLED' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $prophylaxis->description) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('poultry.prophylaxis.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



