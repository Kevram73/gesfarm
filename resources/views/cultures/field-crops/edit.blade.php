@extends('layouts.dashboard')

@section('title', 'Modifier la culture planifiée')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la culture planifiée</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations de la culture planifiée</p>
    </div>

    <form method="POST" action="{{ route('field-crops.update', $fieldCrop->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Champ -->
                <div>
                    <label for="field_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map mr-2 text-gray-400"></i> Champ *
                    </label>
                    <select
                        id="field_id"
                        name="field_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="updateFieldInfo()"
                    >
                        @foreach($fields as $field)
                            @php
                                $availableArea = $field->area - ($field->area_used ?? 0);
                                // Si c'est le champ actuel, ajouter la superficie de cette culture
                                if ($field->id == $fieldCrop->field_id) {
                                    $availableArea += $fieldCrop->area;
                                }
                            @endphp
                            <option 
                                value="{{ $field->id }}" 
                                data-available="{{ $availableArea }}"
                                {{ old('field_id', $fieldCrop->field_id) == $field->id ? 'selected' : '' }}
                            >
                                {{ $field->name }} 
                                ({{ number_format($availableArea, 2) }} ha disponible)
                            </option>
                        @endforeach
                    </select>
                    @error('field_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="field-info" class="mt-2 text-sm text-gray-500"></p>
                </div>

                <!-- Culture -->
                <div>
                    <label for="crop_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-seedling mr-2 text-gray-400"></i> Culture *
                    </label>
                    <select
                        id="crop_id"
                        name="crop_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id', $fieldCrop->crop_id) == $crop->id ? 'selected' : '' }}>
                                {{ $crop->name }}
                                @if($crop->variety)
                                    - {{ $crop->variety }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('crop_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Superficie -->
                <div>
                    <label for="area" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler-combined mr-2 text-gray-400"></i> Superficie (ha) *
                    </label>
                    <input
                        type="number"
                        id="area"
                        name="area"
                        value="{{ old('area', $fieldCrop->area) }}"
                        required
                        step="0.01"
                        min="0.01"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="checkArea()"
                    >
                    @error('area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="area-warning" class="mt-2 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i> Statut
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="PLANTED" {{ old('status', $fieldCrop->status) == 'PLANTED' ? 'selected' : '' }}>Plantée</option>
                        <option value="GROWING" {{ old('status', $fieldCrop->status) == 'GROWING' ? 'selected' : '' }}>En croissance</option>
                        <option value="READY" {{ old('status', $fieldCrop->status) == 'READY' ? 'selected' : '' }}>Prête</option>
                        <option value="HARVESTED" {{ old('status', $fieldCrop->status) == 'HARVESTED' ? 'selected' : '' }}>Récoltée</option>
                        <option value="FAILED" {{ old('status', $fieldCrop->status) == 'FAILED' ? 'selected' : '' }}>Échouée</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Dates -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Dates</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date de plantation -->
                <div>
                    <label for="planting_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i> Date de plantation *
                    </label>
                    <input
                        type="date"
                        id="planting_date"
                        name="planting_date"
                        value="{{ old('planting_date', $fieldCrop->planting_date ? \Carbon\Carbon::parse($fieldCrop->planting_date)->format('Y-m-d') : '') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="updateHarvestDate()"
                    >
                    @error('planting_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de récolte prévue -->
                <div>
                    <label for="expected_harvest_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-gray-400"></i> Date de récolte prévue
                    </label>
                    <input
                        type="date"
                        id="expected_harvest_date"
                        name="expected_harvest_date"
                        value="{{ old('expected_harvest_date', $fieldCrop->expected_harvest_date ? \Carbon\Carbon::parse($fieldCrop->expected_harvest_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('expected_harvest_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de récolte réelle -->
                <div>
                    <label for="actual_harvest_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-times mr-2 text-gray-400"></i> Date de récolte réelle
                    </label>
                    <input
                        type="date"
                        id="actual_harvest_date"
                        name="actual_harvest_date"
                        value="{{ old('actual_harvest_date', $fieldCrop->actual_harvest_date ? \Carbon\Carbon::parse($fieldCrop->actual_harvest_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('actual_harvest_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Quantité -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quantité</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quantité -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight mr-2 text-gray-400"></i> Quantité
                    </label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="{{ old('quantity', $fieldCrop->quantity) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unité -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-gray-400"></i> Unité
                    </label>
                    <input
                        type="text"
                        id="unit"
                        name="unit"
                        value="{{ old('unit', $fieldCrop->unit) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Notes</h2>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                </label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >{{ old('notes', $fieldCrop->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('field-crops.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button
                type="submit"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold"
            >
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function updateFieldInfo() {
        const select = document.getElementById('field_id');
        const selectedOption = select.options[select.selectedIndex];
        const availableArea = parseFloat(selectedOption.getAttribute('data-available')) || 0;
        const infoElement = document.getElementById('field-info');
        
        if (selectedOption.value) {
            infoElement.textContent = `Superficie disponible : ${availableArea.toFixed(2)} ha`;
            infoElement.className = 'mt-2 text-sm text-gray-500';
        } else {
            infoElement.textContent = '';
        }
        
        checkArea();
    }
    
    function checkArea() {
        const select = document.getElementById('field_id');
        const areaInput = document.getElementById('area');
        const warningElement = document.getElementById('area-warning');
        
        if (!select.value || !areaInput.value) {
            warningElement.classList.add('hidden');
            return;
        }
        
        const selectedOption = select.options[select.selectedIndex];
        const availableArea = parseFloat(selectedOption.getAttribute('data-available')) || 0;
        const requestedArea = parseFloat(areaInput.value) || 0;
        
        if (requestedArea > availableArea) {
            warningElement.textContent = `Attention : La superficie demandée (${requestedArea.toFixed(2)} ha) dépasse la superficie disponible (${availableArea.toFixed(2)} ha).`;
            warningElement.classList.remove('hidden');
        } else {
            warningElement.classList.add('hidden');
        }
    }
    
    function updateHarvestDate() {
        const plantingDate = document.getElementById('planting_date').value;
        const harvestDateInput = document.getElementById('expected_harvest_date');
        
        if (plantingDate) {
            harvestDateInput.min = plantingDate;
        }
    }
    
    // Initialiser au chargement
    document.addEventListener('DOMContentLoaded', function() {
        updateFieldInfo();
        updateHarvestDate();
    });
</script>
@endpush
@endsection



