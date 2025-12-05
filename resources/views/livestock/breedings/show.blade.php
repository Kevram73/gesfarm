@extends('layouts.dashboard')

@section('title', 'Détails Saillie')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails de la Saillie</h1>
            <p class="text-gray-600 mt-1">Date : {{ $breeding->date->format('d/m/Y') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('livestock.breedings.edit', $breeding->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('livestock.breedings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Mâle</p>
                <p class="text-sm font-medium text-gray-900">{{ $breeding->male->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Femelle</p>
                <p class="text-sm font-medium text-gray-900">{{ $breeding->female->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="text-sm font-medium text-gray-900">{{ $breeding->type === 'NATURAL' ? 'Naturelle' : 'Insémination Artificielle' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date de vêlage prévue</p>
                <p class="text-sm font-medium text-gray-900">
                    {{ $breeding->expected_calving_date ? $breeding->expected_calving_date->format('d/m/Y') : 'N/A' }}
                    @if($daysRemaining !== null && $daysRemaining > 0)
                        <span class="text-xs text-gray-500">({{ $daysRemaining }} jours restants)</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Statut</p>
                <p class="text-sm font-medium text-gray-900">
                    @if($breeding->success)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Réussie</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                    @endif
                </p>
            </div>
            @if($breeding->calving)
            <div>
                <p class="text-sm text-gray-500">Vêlage</p>
                <p class="text-sm font-medium text-gray-900">
                    <a href="{{ route('livestock.calvings.index') }}" class="text-blue-600 hover:underline">
                        Vêlage enregistré le {{ $breeding->calving->date->format('d/m/Y') }}
                    </a>
                </p>
            </div>
            @endif
        </div>

        @if($breeding->notes)
        <div class="mt-6">
            <p class="text-sm text-gray-500">Notes</p>
            <p class="text-sm text-gray-900 mt-1">{{ $breeding->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection



