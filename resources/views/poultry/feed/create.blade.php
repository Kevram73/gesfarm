@extends('layouts.dashboard')

@section('title', 'Nouvel Enregistrement Alimentation')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouvel Enregistrement d'Alimentation</h1>

        <form action="{{ route('poultry.feed.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot *</label>
                    <select name="livestock_id" id="livestock_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner un lot</option>
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}" {{ old('livestock_id', $selectedLivestock?->id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
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
                    <label for="feed_type" class="block text-sm font-medium text-gray-700 mb-2">Type d'aliment *</label>
                    <input type="text" name="feed_type" id="feed_type" value="{{ old('feed_type') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Ex: Granulés, Fourrage, etc.">
                </div>

                <div>
                    <label for="quantity_grams" class="block text-sm font-medium text-gray-700 mb-2">Quantité (grammes) *</label>
                    <input type="number" name="quantity_grams" id="quantity_grams" value="{{ old('quantity_grams') }}" step="0.1" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('poultry.feed.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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



