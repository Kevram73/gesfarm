@extends('layouts.dashboard')

@section('title', 'Ma Ferme')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Ma Ferme</h1>
            <p class="text-gray-600 mt-1">Informations générales de la ferme</p>
        </div>
        <a href="{{ route('farm.edit') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-edit mr-2"></i> Modifier
        </a>
    </div>

    <!-- Informations principales -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Informations générales</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nom de la ferme</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->code ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->address ?: 'Non renseignée' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ville</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->city ?: 'Non renseignée' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pays</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->country->name ?? 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->phone ?: 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->email ?: 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        @if($farm->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Informations agricoles -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Informations agricoles</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Superficie totale</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $farm->total_area ? number_format($farm->total_area, 2) . ' ha' : 'Non renseignée' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Superficie cultivée</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $farm->cultivated_area ? number_format($farm->cultivated_area, 2) . ' ha' : 'Non renseignée' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type de sol</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->soil_type ?: 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Climat</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->climate ?: 'Non renseigné' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Gestion -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Gestion</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Manager</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($farm->manager)
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                    {{ strtoupper(substr($farm->manager->name, 0, 1)) }}
                                </div>
                                <span>{{ $farm->manager->name }}</span>
                            </div>
                        @else
                            <span class="text-gray-400">Aucun manager assigné</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $farm->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection



