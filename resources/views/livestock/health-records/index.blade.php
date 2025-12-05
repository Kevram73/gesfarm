@extends('layouts.dashboard')

@section('title', 'Fiches Sanitaires')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Fiches Sanitaires</h1>
            <p class="text-gray-600 mt-1">Gestion des soins vétérinaires</p>
        </div>
        <a href="{{ route('livestock.health-records.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Nouvelle fiche
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vétérinaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coût</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($healthRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->livestock->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($record->type === 'VACCINATION')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Vaccination</span>
                                @elseif($record->type === 'TREATMENT')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Traitement</span>
                                @elseif($record->type === 'CHECKUP')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Contrôle</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $record->type }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($record->description, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->veterinarian ?: 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->cost ? number_format($record->cost, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('livestock.health-records.edit', $record->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('livestock.health-records.destroy', $record->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');">
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
                                <p>Aucune fiche sanitaire enregistrée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($healthRecords->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $healthRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



