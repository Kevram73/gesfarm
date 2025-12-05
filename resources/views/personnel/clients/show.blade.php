@extends('layouts.dashboard')

@section('title', 'Détails du client')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <p class="text-gray-600 mt-1">Détails du client</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nom</label>
                    <p class="text-gray-900 font-medium">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Type</label>
                    <p class="text-gray-900">
                        @if($customer->type)
                            {{ ucfirst(strtolower(str_replace('_', ' ', $customer->type))) }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                    <p class="text-gray-900">{{ $customer->email ?: 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Téléphone</label>
                    <p class="text-gray-900">{{ $customer->phone ?: 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Adresse</label>
                    <p class="text-gray-900">{{ $customer->address ?: 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Statut</label>
                    @if($customer->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                    @endif
                </div>
                @if($customer->notes)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                    <p class="text-gray-900">{{ $customer->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($customer->harvests->count() > 0 || $customer->payments->count() > 0)
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Historique</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Récoltes</label>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->harvests->count() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Paiements</label>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->payments->count() }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection



