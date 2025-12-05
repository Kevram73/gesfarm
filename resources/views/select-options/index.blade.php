@extends('layouts.dashboard')

@section('title', 'Gestion des Options')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-cog text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Gestion des Options</h1>
                    <p class="text-gray-600 mt-1">
                        Configurez les options disponibles dans les menus déroulants
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($sections as $section)
                <div class="bg-white rounded-lg shadow-md border-2 border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <button 
                            onclick="toggleSection('{{ $section['title'] }}')"
                            class="flex items-center justify-between w-full text-left group"
                        >
                            <div class="flex items-center gap-4">
                                <div class="p-2 rounded-lg bg-gray-100 text-green-600">
                                    <i class="fas fa-cog text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 group-hover:text-gray-700 transition-colors">
                                        {{ $section['title'] }}
                                    </h2>
                                    @php
                                        $totalOptions = collect($section['categories'])->sum(function($cat) use ($optionsByCategory) {
                                            return isset($optionsByCategory[$cat['value']]) ? $optionsByCategory[$cat['value']]->count() : 0;
                                        });
                                    @endphp
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $totalOptions }} option{{ $totalOptions > 1 ? 's' : '' }} • {{ count($section['categories']) }} catégorie{{ count($section['categories']) > 1 ? 's' : '' }}
                                    </p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 transition-transform" id="icon-{{ $section['title'] }}"></i>
                        </button>
                    </div>
                    
                    <div id="section-{{ $section['title'] }}" class="hidden p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach($section['categories'] as $category)
                                @php
                                    $options = $optionsByCategory[$category['value']] ?? collect();
                                @endphp
                                <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm hover:border-gray-300 transition-all">
                                    <div class="p-4 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-2xl">{{ $category['icon'] }}</span>
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    {{ $category['label'] }}
                                                </h3>
                                            </div>
                                            <button
                                                onclick="showAddForm('{{ $category['value'] }}')"
                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded shadow-sm transition-colors"
                                            >
                                                <i class="fas fa-plus mr-1"></i> Ajouter
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2 ml-10">
                                            {{ $options->count() }} option{{ $options->count() > 1 ? 's' : '' }} disponible{{ $options->count() > 1 ? 's' : '' }}
                                        </p>
                                    </div>

                                    <div class="p-4">
                                        <!-- Formulaire d'ajout/modification -->
                                        <div id="form-{{ $category['value'] }}" class="hidden mb-4 p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                                            <form id="option-form-{{ $category['value'] }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="category" value="{{ $category['value'] }}">
                                                <input type="hidden" name="option_id" id="option-id-{{ $category['value'] }}">
                                                
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur *</label>
                                                        <input
                                                            type="text"
                                                            name="value"
                                                            id="value-{{ $category['value'] }}"
                                                            required
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                            placeholder="Ex: CATTLE, Argileux"
                                                        >
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                                                        <input
                                                            type="text"
                                                            name="label"
                                                            id="label-{{ $category['value'] }}"
                                                            required
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                            placeholder="Ex: Bovin, Argileux"
                                                        >
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                        <input
                                                            type="text"
                                                            name="description"
                                                            id="description-{{ $category['value'] }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                            placeholder="Description optionnelle"
                                                        >
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                                                            <input
                                                                type="number"
                                                                name="order"
                                                                id="order-{{ $category['value'] }}"
                                                                value="0"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                            >
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                                            <select
                                                                name="is_active"
                                                                id="is_active-{{ $category['value'] }}"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                            >
                                                                <option value="1">Actif</option>
                                                                <option value="0">Inactif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2 pt-2">
                                                        <button
                                                            type="submit"
                                                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow-sm transition-colors"
                                                        >
                                                            <i class="fas fa-save mr-2"></i> Enregistrer
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onclick="hideForm('{{ $category['value'] }}')"
                                                            class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded transition-colors"
                                                        >
                                                            Annuler
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Liste des options -->
                                        <div class="space-y-2 max-h-96 overflow-y-auto">
                                            @if($options->isEmpty())
                                                <div class="text-center py-8 px-4">
                                                    <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-plus text-gray-400 text-2xl"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500 font-medium">
                                                        Aucune option disponible
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        Cliquez sur "Ajouter" pour créer une option
                                                    </p>
                                                </div>
                                            @else
                                                @foreach($options->sortBy('order') as $index => $option)
                                                    <div class="group flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-green-300 hover:shadow-md transition-all">
                                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                                                {{ $index + 1 }}
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <span class="font-semibold text-gray-900 truncate">
                                                                        {{ $option->label }}
                                                                    </span>
                                                                    @if($option->is_active)
                                                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                                                    @else
                                                                        <i class="far fa-circle text-gray-400 text-sm"></i>
                                                                    @endif
                                                                </div>
                                                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                                                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">
                                                                        {{ $option->value }}
                                                                    </span>
                                                                    @if($option->description)
                                                                        <span class="truncate">{{ $option->description }}</span>
                                                                    @endif
                                                                    <span class="text-gray-400">•</span>
                                                                    <span>Ordre: {{ $option->order }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-1 ml-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <button
                                                                onclick="editOption('{{ $category['value'] }}', {{ $option->id }}, '{{ addslashes($option->value) }}', '{{ addslashes($option->label) }}', '{{ addslashes($option->description ?? '') }}', {{ $option->order }}, {{ $option->is_active ? 1 : 0 }})"
                                                                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                                                title="Modifier"
                                                            >
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form 
                                                                method="POST" 
                                                                action="{{ route('select-options.destroy', $option->id) }}"
                                                                class="inline"
                                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette option ?')"
                                                            >
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                                                    title="Supprimer"
                                                                >
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleSection(sectionTitle) {
        const section = document.getElementById('section-' + sectionTitle);
        const icon = document.getElementById('icon-' + sectionTitle);
        
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            icon.classList.add('fa-chevron-up');
            icon.classList.remove('fa-chevron-down');
        } else {
            section.classList.add('hidden');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    function showAddForm(category) {
        const form = document.getElementById('form-' + category);
        const optionId = document.getElementById('option-id-' + category);
        const formElement = document.getElementById('option-form-' + category);
        
        // Réinitialiser le formulaire
        optionId.value = '';
        document.getElementById('value-' + category).value = '';
        document.getElementById('label-' + category).value = '';
        document.getElementById('description-' + category).value = '';
        document.getElementById('order-' + category).value = '0';
        document.getElementById('is_active-' + category).value = '1';
        
        // Changer l'action du formulaire
        formElement.action = '{{ route("select-options.store") }}';
        formElement.method = 'POST';
        
        form.classList.remove('hidden');
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideForm(category) {
        document.getElementById('form-' + category).classList.add('hidden');
    }

    function editOption(category, id, value, label, description, order, isActive) {
        const form = document.getElementById('form-' + category);
        const formElement = document.getElementById('option-form-' + category);
        
        // Remplir le formulaire
        document.getElementById('option-id-' + category).value = id;
        document.getElementById('value-' + category).value = value;
        document.getElementById('label-' + category).value = label;
        document.getElementById('description-' + category).value = description;
        document.getElementById('order-' + category).value = order;
        document.getElementById('is_active-' + category).value = isActive;
        
        // Changer l'action du formulaire
        formElement.action = '{{ route("select-options.update", ":id") }}'.replace(':id', id);
        formElement.method = 'POST';
        
        // Ajouter le champ _method pour PUT
        if (!formElement.querySelector('input[name="_method"]')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            formElement.appendChild(methodInput);
        }
        
        form.classList.remove('hidden');
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Ouvrir la première section par défaut
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('[onclick*="Général"]')) {
            toggleSection('Général');
        }
    });
</script>
@endpush
    </div>
</div>
@endsection

