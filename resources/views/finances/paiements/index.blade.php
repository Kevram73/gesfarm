@extends('layouts.dashboard')

@section('title', 'Paiements')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Paiements</h1>
            <p class="text-gray-600 mt-1">Gérez vos paiements</p>
        </div>
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-plus mr-2"></i> Nouveau paiement
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('payments.index') }}" class="flex items-center space-x-4 flex-wrap gap-4">
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                <option value="INCOME" {{ request('type') == 'INCOME' ? 'selected' : '' }}>Revenus</option>
                <option value="EXPENSE" {{ request('type') == 'EXPENSE' ? 'selected' : '' }}>Dépenses</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>En attente</option>
                <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Complété</option>
                <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Échoué</option>
            </select>
            <select name="customer_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Tous les clients</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Récolte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') : \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->type === 'INCOME')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-down mr-1"></i> Revenu
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-up mr-1"></i> Dépense
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $payment->type === 'INCOME' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $payment->type === 'INCOME' ? '+' : '-' }}{{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->customer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($payment->harvest)
                                    {{ $payment->harvest->crop->name ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($payment->method)
                                    {{ ucfirst(str_replace('_', ' ', strtolower($payment->method))) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'COMPLETED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Complété</span>
                                @elseif($payment->status === 'PENDING')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                @elseif($payment->status === 'FAILED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Échoué</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('payments.edit', $payment->id) }}" class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-money-bill-wave text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Aucun paiement enregistré</p>
                                <p class="text-sm mt-2">Commencez par créer votre premier paiement</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
