@extends('layouts.dashboard')

@section('title', 'Poussins')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Poussins</h1>
            <p class="text-gray-600 mt-1">Gestion des poussins</p>
        </div>
        <a href="{{ route('poultry.chicks.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Nouveau poussin
        </a>
    </div>

    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Actifs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Poids moyen</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_weight'] ?? 0, 2) }} g</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date éclosion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Âge (jours)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poids initial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poids actuel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($chicks as $chick)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $chick->name ?: 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chick->hatch_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chick->age ?? 0 }} jours</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chick->initial_weight ? number_format($chick->initial_weight, 2) . ' g' : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $chick->current_weight ? number_format($chick->current_weight, 2) . ' g' : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($chick->status === 'ACTIVE')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                @elseif($chick->status === 'DECEASED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Décédé</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('poultry.chicks.edit', $chick->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('poultry.chicks.destroy', $chick->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <p>Aucun poussin enregistré</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($chicks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $chicks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



