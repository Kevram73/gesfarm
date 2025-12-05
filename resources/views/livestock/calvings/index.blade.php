@extends('layouts.dashboard')

@section('title', 'Vêlages')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Vêlages</h1>
            <p class="text-gray-600 mt-1">Gestion des vêlages et agnelages</p>
        </div>
        <a href="{{ route('livestock.calvings.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Nouveau vêlage
        </a>
    </div>

    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Ce mois</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['this_month'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Petits nés</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_offspring'] ?? 0 }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mère</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petits nés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Complications</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($calvings as $calving)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $calving->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $calving->mother->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($calving->type === 'NORMAL')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Normal</span>
                                @elseif($calving->type === 'DIFFICULT')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Difficile</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Césarienne</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $calving->offspring_count }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $calving->complications ?: 'Aucune' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('livestock.calvings.edit', $calving->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('livestock.calvings.destroy', $calving->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');">
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
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <p>Aucun vêlage enregistré</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($calvings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $calvings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



