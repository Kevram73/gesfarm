@extends('layouts.dashboard')

@section('title', 'Nouvelle Prophylaxie')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouvelle Prophylaxie</h1>

        <form action="{{ route('poultry.prophylaxis.store') }}" method="POST" id="prophylaxisForm">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">Lot *</label>
                    <select name="livestock_id" id="livestock_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Sélectionner un lot</option>
                        @foreach($livestock as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du traitement *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Durée (jours) *</label>
                        <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') }}" min="1" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Actions quotidiennes *</label>
                    <div id="dailyActions">
                        <div class="daily-action-item mb-2 flex gap-2">
                            <input type="number" name="daily_actions[0][day]" placeholder="Jour" min="1" required
                                class="w-24 px-4 py-2 border border-gray-300 rounded-lg">
                            <input type="text" name="daily_actions[0][action]" placeholder="Action à effectuer" required
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addDailyAction()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Ajouter une action
                    </button>
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

@push('scripts')
<script>
let actionIndex = 1;
function addDailyAction() {
    const container = document.getElementById('dailyActions');
    const div = document.createElement('div');
    div.className = 'daily-action-item mb-2 flex gap-2';
    div.innerHTML = `
        <input type="number" name="daily_actions[${actionIndex}][day]" placeholder="Jour" min="1" required
            class="w-24 px-4 py-2 border border-gray-300 rounded-lg">
        <input type="text" name="daily_actions[${actionIndex}][action]" placeholder="Action à effectuer" required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
    actionIndex++;
}
</script>
@endpush
@endsection



