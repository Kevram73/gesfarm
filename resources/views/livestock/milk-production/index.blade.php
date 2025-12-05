@extends('layouts.dashboard')

@section('title', 'Production Laitière')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Production Laitière</h1>
            <p class="text-gray-600 mt-1">Suivi quotidien de la production de lait</p>
        </div>
        <a href="{{ route('livestock.milk-production.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Nouvelle production
        </a>
    </div>

    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Aujourd'hui</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['today_liters'] ?? 0, 2) }} L</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Cette semaine</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['week_liters'] ?? 0, 2) }} L</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Ce mois</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['month_liters'] ?? 0, 2) }} L</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Moyenne/jour</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_daily'] ?? 0, 2) }} L</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité (L)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qualité</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productions as $production)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $production->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $production->livestock->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($production->quantity, 2) }} L</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $production->quality ?: 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('livestock.milk-production.edit', $production->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('livestock.milk-production.destroy', $production->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');">
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
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <p>Aucune production enregistrée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($productions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $productions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



