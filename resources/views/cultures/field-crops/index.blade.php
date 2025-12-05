@extends('layouts.dashboard')

@section('title', 'Cultures dans les champs')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Cultures dans les champs</h1>
            <p class="text-gray-600 mt-1">Gérez les cultures planifiées dans vos champs</p>
        </div>
        <a href="{{ route('field-crops.create', ['field_id' => $fieldId]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-plus mr-2"></i> Planifier une culture
        </a>
    </div>

    <!-- Filtre par champ -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('field-crops.index') }}" class="flex items-center space-x-4">
            <label for="field_id" class="text-sm font-medium text-gray-700">Filtrer par champ :</label>
            <select
                id="field_id"
                name="field_id"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                onchange="this.form.submit()"
            >
                <option value="">Tous les champs</option>
                @foreach($fields as $field)
                    <option value="{{ $field->id }}" {{ $fieldId == $field->id ? 'selected' : '' }}>
                        {{ $field->name }} ({{ number_format($field->area, 2) }} ha)
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Liste des cultures -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Champ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Culture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Superficie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de plantation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Récolte prévue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($fieldCrops as $fieldCrop)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $fieldCrop->field->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-seedling text-green-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $fieldCrop->crop->name }}</div>
                                        @if($fieldCrop->crop->variety)
                                            <div class="text-sm text-gray-500">{{ $fieldCrop->crop->variety }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $fieldCrop->area ? number_format($fieldCrop->area, 2) . ' ha' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $fieldCrop->planting_date ? \Carbon\Carbon::parse($fieldCrop->planting_date)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($fieldCrop->expected_harvest_date)
                                    {{ \Carbon\Carbon::parse($fieldCrop->expected_harvest_date)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($fieldCrop->status === 'PLANTED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Plantée
                                    </span>
                                @elseif($fieldCrop->status === 'GROWING')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        En croissance
                                    </span>
                                @elseif($fieldCrop->status === 'READY')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Prête
                                    </span>
                                @elseif($fieldCrop->status === 'HARVESTED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Récoltée
                                    </span>
                                @elseif($fieldCrop->status === 'FAILED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Échouée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('field-crops.edit', $fieldCrop->id) }}" class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('field-crops.destroy', $fieldCrop->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette culture ?');">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-seedling text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Aucune culture planifiée</p>
                                <p class="text-sm mt-2">Commencez par planifier une culture dans un champ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($fieldCrops->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $fieldCrops->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



