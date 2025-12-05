@extends('layouts.dashboard')

@section('title', 'Modifier l\'équipement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier l'équipement</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations de l'équipement</p>
    </div>

    <form method="POST" action="{{ route('equipment.update', $equipment->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tools mr-2 text-gray-400"></i> Nom *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $equipment->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i> Type
                    </label>
                    <select id="type" name="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->value }}" {{ old('type', $equipment->type) == $type->value ? 'selected' : '' }}>
                                {{ $type->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-truck mr-2 text-gray-400"></i> Fournisseur
                    </label>
                    <select id="supplier_id" name="supplier_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $equipment->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-cog mr-2 text-gray-400"></i> Modèle
                    </label>
                    <input type="text" id="model" name="model" value="{{ old('model', $equipment->model) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-barcode mr-2 text-gray-400"></i> Numéro de série
                    </label>
                    <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i> Statut
                    </label>
                    <select id="status" name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="ACTIVE" {{ old('status', $equipment->status) == 'ACTIVE' ? 'selected' : '' }}>Actif</option>
                        <option value="INACTIVE" {{ old('status', $equipment->status) == 'INACTIVE' ? 'selected' : '' }}>Inactif</option>
                        <option value="MAINTENANCE" {{ old('status', $equipment->status) == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                        <option value="REPAIR" {{ old('status', $equipment->status) == 'REPAIR' ? 'selected' : '' }}>Réparation</option>
                    </select>
                </div>

                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i> Date d'achat
                    </label>
                    <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $equipment->purchase_date ? \Carbon\Carbon::parse($equipment->purchase_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i> Prix d'achat (FCFA)
                    </label>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-gray-400"></i> Quantité
                    </label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $equipment->quantity) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-gray-400"></i> Unité
                    </label>
                    <input type="text" id="unit" name="unit" value="{{ old('unit', $equipment->unit) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2 text-gray-400"></i> Stock minimum
                    </label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $equipment->min_stock) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="max_stock" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-arrow-up mr-2 text-gray-400"></i> Stock maximum
                    </label>
                    <input type="number" id="max_stock" name="max_stock" value="{{ old('max_stock', $equipment->max_stock) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-wrench mr-2 text-gray-400"></i> Dernière maintenance
                    </label>
                    <input type="date" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', $equipment->maintenance_date ? \Carbon\Carbon::parse($equipment->maintenance_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="next_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-gray-400"></i> Prochaine maintenance
                    </label>
                    <input type="date" id="next_maintenance" name="next_maintenance" value="{{ old('next_maintenance', $equipment->next_maintenance ? \Carbon\Carbon::parse($equipment->next_maintenance)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes', $equipment->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('equipment.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection



