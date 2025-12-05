@extends('layouts.dashboard')

@section('title', 'Nouvelle Saillie')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouvelle Saillie</h1>

        <form action="{{ route('livestock.breedings.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="male_id" class="block text-sm font-medium text-gray-700 mb-2">Mâle *</label>
                    <select name="male_id" id="male_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner un mâle</option>
                        @foreach($males as $male)
                            <option value="{{ $male->id }}">{{ $male->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="female_id" class="block text-sm font-medium text-gray-700 mb-2">Femelle *</label>
                    <select name="female_id" id="female_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner une femelle</option>
                        @foreach($females as $female)
                            <option value="{{ $female->id }}">{{ $female->name }}</option>
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
                        <option value="NATURAL" {{ old('type') == 'NATURAL' ? 'selected' : '' }}>Naturelle</option>
                        <option value="ARTIFICIAL_INSEMINATION" {{ old('type') == 'ARTIFICIAL_INSEMINATION' ? 'selected' : '' }}>Insémination Artificielle</option>
                    </select>
                </div>

                <div>
                    <label for="success" class="flex items-center">
                        <input type="checkbox" name="success" id="success" value="1" {{ old('success') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Saillie réussie</span>
                    </label>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('livestock.breedings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



