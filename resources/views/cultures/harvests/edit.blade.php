@extends('layouts.dashboard')

@section('title', 'Modifier la récolte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la récolte</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations de la récolte</p>
    </div>

    <form method="POST" action="{{ route('harvests.update', $harvest->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i> Date de récolte *
                    </label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date', $harvest->date ? \Carbon\Carbon::parse($harvest->date)->format('Y-m-d') : '') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        @foreach($harvestStatuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $harvest->status) == $status->value ? 'selected' : '' }}>
                                {{ $status->label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        <option value="">Sélectionner une culture</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id', $harvest->crop_id) == $crop->id ? 'selected' : '' }}>
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

                <!-- Champ -->
                <div>
                    <label for="field_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map mr-2 text-gray-400"></i> Champ
                    </label>
                    <select
                        id="field_id"
                        name="field_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner un champ</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}" {{ old('field_id', $harvest->field_id) == $field->id ? 'selected' : '' }}>
                                {{ $field->name }}
                                @if($field->area)
                                    ({{ number_format($field->area, 2) }} ha)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('field_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Client -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i> Client
                    </label>
                    <select
                        id="customer_id"
                        name="customer_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Sélectionner un client</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $harvest->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Quantité et prix -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quantité et prix</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quantité -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight mr-2 text-gray-400"></i> Quantité *
                    </label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="{{ old('quantity', $harvest->quantity) }}"
                        required
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="calculateTotal()"
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
                        value="{{ old('unit', $harvest->unit) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix par unité -->
                <div>
                    <label for="price_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i> Prix par unité (FCFA)
                    </label>
                    <input
                        type="number"
                        id="price_per_unit"
                        name="price_per_unit"
                        value="{{ old('price_per_unit', $harvest->price_per_unit) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onchange="calculateTotal()"
                    >
                    @error('price_per_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix total -->
                <div>
                    <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-gray-400"></i> Prix total (FCFA)
                    </label>
                    <input
                        type="number"
                        id="total_price"
                        name="total_price"
                        value="{{ old('total_price', $harvest->total_price) }}"
                        step="0.01"
                        min="0"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    @error('total_price')
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
                >{{ old('notes', $harvest->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('harvests.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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
    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const pricePerUnit = parseFloat(document.getElementById('price_per_unit').value) || 0;
        const total = quantity * pricePerUnit;
        document.getElementById('total_price').value = total.toFixed(2);
    }

    document.getElementById('quantity').addEventListener('input', calculateTotal);
    document.getElementById('price_per_unit').addEventListener('input', calculateTotal);
    
    // Calculer au chargement
    calculateTotal();
</script>
@endpush
@endsection



