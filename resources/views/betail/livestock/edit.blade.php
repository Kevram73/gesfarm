@extends('layouts.dashboard')

@section('title', 'Modifier le bétail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier le bétail</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations du bétail</p>
    </div>

    <form method="POST" action="{{ route('livestock.update', $livestock->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i> Nom *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $livestock->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-cow mr-2 text-gray-400"></i> Type *
                    </label>
                    <select id="type" name="type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        @foreach($livestockTypes as $type)
                            <option value="{{ $type->value }}" {{ old('type', $livestock->type) == $type->value ? 'selected' : '' }}>
                                {{ $type->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dna mr-2 text-gray-400"></i> Race
                    </label>
                    <select id="breed" name="breed"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner une race</option>
                        @foreach($breeds as $breed)
                            <option value="{{ $breed->value }}" {{ old('breed', $livestock->breed) == $breed->value ? 'selected' : '' }}>
                                {{ $breed->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-gray-400"></i> Parent
                    </label>
                    <select id="parent_id" name="parent_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Aucun parent</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $livestock->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }} ({{ $parent->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-venus-mars mr-2 text-gray-400"></i> Sexe
                    </label>
                    <select id="gender" name="gender"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        <option value="MALE" {{ old('gender', $livestock->gender) == 'MALE' ? 'selected' : '' }}>Mâle</option>
                        <option value="FEMALE" {{ old('gender', $livestock->gender) == 'FEMALE' ? 'selected' : '' }}>Femelle</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i> Statut
                    </label>
                    <select id="status" name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="ACTIVE" {{ old('status', $livestock->status) == 'ACTIVE' ? 'selected' : '' }}>Actif</option>
                        <option value="INACTIVE" {{ old('status', $livestock->status) == 'INACTIVE' ? 'selected' : '' }}>Inactif</option>
                        <option value="SOLD" {{ old('status', $livestock->status) == 'SOLD' ? 'selected' : '' }}>Vendu</option>
                        <option value="DECEASED" {{ old('status', $livestock->status) == 'DECEASED' ? 'selected' : '' }}>Décédé</option>
                    </select>
                </div>

                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-birthday-cake mr-2 text-gray-400"></i> Âge (ans)
                    </label>
                    <input type="number" id="age" name="age" value="{{ old('age', $livestock->age) }}" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight mr-2 text-gray-400"></i> Poids (kg)
                    </label>
                    <input type="number" id="weight" name="weight" value="{{ old('weight', $livestock->weight) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i> Date d'achat
                    </label>
                    <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $livestock->purchase_date ? \Carbon\Carbon::parse($livestock->purchase_date)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i> Prix d'achat (FCFA)
                    </label>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $livestock->purchase_price) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-gray-400"></i> Quantité
                    </label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $livestock->quantity) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2 text-gray-400"></i> Stock minimum
                    </label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $livestock->min_stock) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="max_stock" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-arrow-up mr-2 text-gray-400"></i> Stock maximum
                    </label>
                    <input type="number" id="max_stock" name="max_stock" value="{{ old('max_stock', $livestock->max_stock) }}" step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes', $livestock->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('livestock.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection



