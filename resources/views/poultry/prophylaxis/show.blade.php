@extends('layouts.dashboard')

@section('title', 'Détails Prophylaxie')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails de la Prophylaxie</h1>
            <p class="text-gray-600 mt-1">{{ $prophylaxis->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('poultry.prophylaxis.edit', $prophylaxis->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('poultry.prophylaxis.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Lot</p>
                    <p class="text-sm font-medium text-gray-900">{{ $prophylaxis->livestock->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de début</p>
                    <p class="text-sm font-medium text-gray-900">{{ $prophylaxis->start_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Durée</p>
                    <p class="text-sm font-medium text-gray-900">{{ $prophylaxis->duration_days }} jours</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="text-sm text-gray-900">{{ $prophylaxis->description ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Progression</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Progression</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-green-600 h-4 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Actions complétées</p>
                    <p class="text-lg font-bold text-gray-900">{{ $completedActions }} / {{ $totalActions }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Quotidiennes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($prophylaxis->dailyActions as $action)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jour {{ $action->day }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $action->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $action->action }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($action->completed)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Complétée</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$action->completed)
                                    <form action="{{ route('poultry.prophylaxis.actions.complete', [$prophylaxis->id, $action->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm">
                                            <i class="fas fa-check mr-1"></i> Marquer comme complétée
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



