@extends('layouts.dashboard')

@section('title', 'Saillies')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Saillies</h1>
            <p class="text-gray-600 mt-1">Gestion des saillies et inséminations</p>
        </div>
        <a href="{{ route('livestock.breedings.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i> Nouvelle saillie
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mâle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Femelle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vêlage prévu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($breedings as $breeding)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $breeding->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $breeding->male->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $breeding->female->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($breeding->type === 'NATURAL')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-heart mr-1"></i> Naturelle
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-flask mr-1"></i> IA
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($breeding->expected_calving_date)
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                        <span>{{ $breeding->expected_calving_date->format('d/m/Y') }}</span>
                                        @if($breeding->expected_calving_date->isFuture() && !$breeding->calving)
                                            @php
                                                $daysRemaining = now()->diffInDays($breeding->expected_calving_date);
                                            @endphp
                                            @if($daysRemaining <= 30)
                                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    {{ $daysRemaining }}j
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($breeding->success)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Réussie</span>
                                @elseif($breeding->calving)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Vêlée</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('livestock.breedings.show', $breeding->id) }}" class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('livestock.breedings.edit', $breeding->id) }}" class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('livestock.breedings.destroy', $breeding->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette saillie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-heart text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Aucune saillie enregistrée</p>
                                    <p class="text-sm text-gray-500 mb-4">Commencez par créer votre première saillie</p>
                                    <a href="{{ route('livestock.breedings.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold">
                                        <i class="fas fa-plus mr-2"></i> Créer une saillie
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($breedings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $breedings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

