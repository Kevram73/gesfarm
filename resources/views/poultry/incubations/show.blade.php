@extends('layouts.dashboard')

@section('title', 'Détails Incubation')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails de l'Incubation</h1>
            <p class="text-gray-600 mt-1">Suivi de l'incubation #{{ $incubation->id }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('poultry.incubations.edit', $incubation->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('poultry.incubations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Date de début</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->poultry_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Race</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->breed ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nombre d'œufs</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->egg_count }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Température</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->temperature }}°C</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Humidité</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->humidity }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jours d'incubation</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->incubation_days }} jours</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date d'éclosion prévue</p>
                        <p class="text-sm font-medium text-gray-900">{{ $incubation->expected_hatch_date ? $incubation->expected_hatch_date->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression -->
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
                    <p class="text-sm text-gray-500">Statut</p>
                    @if($incubation->status === 'COMPLETED')
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Terminée</span>
                    @elseif($incubation->status === 'IN_PROGRESS')
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En cours</span>
                    @else
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Échouée</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Taux d'éclosion</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($hatchRate, 1) }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Œufs éclos</p>
                    <p class="text-lg font-bold text-gray-900">{{ $incubation->hatched_count }} / {{ $incubation->egg_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Poussins -->
    @if($incubation->chicks->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Poussins issus de cette incubation</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'éclosion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poids initial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($incubation->chicks as $chick)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chick->name ?: 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chick->hatch_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chick->initial_weight ? number_format($chick->initial_weight, 2) . ' g' : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($chick->status === 'ACTIVE')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $chick->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection



