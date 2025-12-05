@extends('layouts.dashboard')

@section('title', 'Modifier le paiement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Modifier le paiement</h1>
        <p class="text-gray-600 mt-1">Mettez à jour les informations du paiement</p>
    </div>

    <form method="POST" action="{{ route('payments.update', $payment->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exchange-alt mr-2 text-gray-400"></i> Type *
                    </label>
                    <select id="type" name="type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="INCOME" {{ old('type', $payment->type) == 'INCOME' ? 'selected' : '' }}>Revenu</option>
                        <option value="EXPENSE" {{ old('type', $payment->type) == 'EXPENSE' ? 'selected' : '' }}>Dépense</option>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i> Montant (FCFA) *
                    </label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required step="0.01" min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="harvest_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-wheat-awn mr-2 text-gray-400"></i> Récolte
                    </label>
                    <select id="harvest_id" name="harvest_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner une récolte</option>
                        @foreach($harvests as $harvest)
                            <option value="{{ $harvest->id }}" {{ old('harvest_id', $payment->harvest_id) == $harvest->id ? 'selected' : '' }}>
                                {{ $harvest->crop->name ?? 'N/A' }} - {{ \Carbon\Carbon::parse($harvest->date)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i> Client
                    </label>
                    <select id="customer_id" name="customer_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un client</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $payment->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i> Statut
                    </label>
                    <select id="status" name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="PENDING" {{ old('status', $payment->status) == 'PENDING' ? 'selected' : '' }}>En attente</option>
                        <option value="COMPLETED" {{ old('status', $payment->status) == 'COMPLETED' ? 'selected' : '' }}>Complété</option>
                        <option value="FAILED" {{ old('status', $payment->status) == 'FAILED' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>

                <div>
                    <label for="method" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-credit-card mr-2 text-gray-400"></i> Méthode de paiement
                    </label>
                    <select id="method" name="method"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner</option>
                        <option value="CASH" {{ old('method', $payment->method) == 'CASH' ? 'selected' : '' }}>Espèces</option>
                        <option value="CARD" {{ old('method', $payment->method) == 'CARD' ? 'selected' : '' }}>Carte</option>
                        <option value="BANK_TRANSFER" {{ old('method', $payment->method) == 'BANK_TRANSFER' ? 'selected' : '' }}>Virement bancaire</option>
                        <option value="MOBILE_MONEY" {{ old('method', $payment->method) == 'MOBILE_MONEY' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="CHECK" {{ old('method', $payment->method) == 'CHECK' ? 'selected' : '' }}>Chèque</option>
                    </select>
                </div>

                <div>
                    <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i> Date de paiement
                    </label>
                    <input type="date" id="paid_at" name="paid_at" value="{{ old('paid_at', $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-hashtag mr-2 text-gray-400"></i> Référence
                    </label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference', $payment->reference) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes', $payment->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('payments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
