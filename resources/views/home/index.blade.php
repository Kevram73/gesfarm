@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
        <p class="text-gray-600 mt-1">Bienvenue, {{ Auth::user()->name }} !</p>
    </div>

    <!-- Informations de la ferme -->
    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $farm->name }}</h2>
                @if($farm->address)
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $farm->address }}
                        @if($farm->city)
                            , {{ $farm->city }}
                        @endif
                    </p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                @if($farm->is_active)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Active
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Revenus -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus Total</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> {{ number_format($stats['revenue_this_month'], 0, ',', ' ') }} FCFA ce mois
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Tâches -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tâches</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_tasks'] }}</p>
                    <p class="text-xs text-yellow-600 mt-1">
                        {{ $stats['pending_tasks'] }} en attente
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-tasks text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Récoltes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Récoltes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_harvests'] }}</p>
                    <p class="text-xs text-purple-600 mt-1">
                        {{ $stats['harvests_this_month'] }} ce mois
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-seedling text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Avicoles et Ruminants -->
    @if(isset($stats['poultry_mortality_rate']) || isset($stats['eggs_per_day']) || isset($stats['milk_per_day']))
    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-md p-6 mb-6 border-l-4 border-green-500">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-chart-line mr-2 text-green-600"></i> Indicateurs de Performance
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if(isset($stats['poultry_mortality_rate']))
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Mortalité Volailles</p>
                        <p class="text-2xl font-bold {{ $stats['poultry_mortality_rate'] > 10 ? 'text-red-600' : 'text-green-600' }} mt-1">
                            {{ $stats['poultry_mortality_rate'] }}%
                        </p>
                    </div>
                    <div class="p-2 bg-red-100 rounded-full">
                        <i class="fas fa-skull text-red-600"></i>
                    </div>
                </div>
            </div>
            @endif
            
            @if(isset($stats['eggs_per_day']))
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Ponte Aujourd'hui</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">
                            {{ $stats['eggs_per_day'] ?? 0 }} œufs
                        </p>
                    </div>
                    <div class="p-2 bg-orange-100 rounded-full">
                        <i class="fas fa-egg text-orange-600"></i>
                    </div>
                </div>
            </div>
            @endif
            
            @if(isset($stats['milk_per_day']))
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Lait Aujourd'hui</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">
                            {{ number_format($stats['milk_per_day'] ?? 0, 2) }} L
                        </p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-full">
                        <i class="fas fa-fill-drip text-blue-600"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Alertes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @if(isset($upcomingVaccinations) && $upcomingVaccinations->count() > 0)
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-syringe text-blue-600 mr-2"></i> Prochains Vaccins
                    </h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $upcomingVaccinations->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($upcomingVaccinations->take(5) as $vaccination)
                        <div class="p-2 bg-blue-50 rounded">
                            <p class="text-sm font-medium text-gray-900">{{ $vaccination->livestock->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600">{{ $vaccination->date->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(isset($stockAlerts) && $stockAlerts->count() > 0)
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i> Ruptures de Stock
                    </h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        {{ $stockAlerts->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($stockAlerts->take(5) as $item)
                        <div class="p-2 bg-red-50 rounded">
                            <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                            <p class="text-xs text-red-600">Stock: {{ number_format($item->current_stock, 2) }} {{ $item->unit }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tâches du jour -->
    @if(isset($todayTasks) && $todayTasks->count() > 0)
    <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-tasks text-yellow-600 mr-2"></i> Tâches du Jour
                </h3>
                <a href="{{ route('farm-tasks.index') }}" class="text-sm text-green-600 hover:text-green-700">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @foreach($todayTasks->take(5) as $task)
                    <div class="p-3 bg-yellow-50 rounded-lg flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                            <p class="text-xs text-gray-600">{{ $task->assignedTo->name ?? 'Non assigné' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $task->priority === 'HIGH' ? 'bg-red-100 text-red-800' : ($task->priority === 'MEDIUM' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques secondaires -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clients -->
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Clients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Employés -->
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Employés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Cultures -->
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cultures</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_crops'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-leaf text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Champs -->
        <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow border-t-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Champs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_fields'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-map text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques bétail et équipements -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Bétail -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bétail</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_livestock'] }}</p>
                </div>
                <i class="fas fa-cow text-gray-400 text-xl"></i>
            </div>
        </div>

        <!-- Équipements -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Équipements</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_equipment'] }}</p>
                </div>
                <i class="fas fa-tools text-gray-400 text-xl"></i>
            </div>
        </div>

        <!-- Inventaire -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Inventaire</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_inventory_items'] }}</p>
                </div>
                <i class="fas fa-boxes text-gray-400 text-xl"></i>
            </div>
        </div>

        <!-- Stock faible -->
        <div class="bg-white rounded-lg shadow p-4 {{ $stats['low_stock_items'] > 0 ? 'border-l-4 border-red-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stock faible</p>
                    <p class="text-2xl font-bold {{ $stats['low_stock_items'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $stats['low_stock_items'] }}</p>
                </div>
                <i class="fas fa-exclamation-triangle {{ $stats['low_stock_items'] > 0 ? 'text-red-400' : 'text-gray-400' }} text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Alertes Semis/Récolte -->
    @if((isset($upcomingPlantings) && $upcomingPlantings->count() > 0) || (isset($upcomingHarvests) && $upcomingHarvests->count() > 0))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @if(isset($upcomingPlantings) && $upcomingPlantings->count() > 0)
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-seedling text-green-600 mr-2"></i> Semis à venir (7 jours)
                    </h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $upcomingPlantings->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($upcomingPlantings->take(5) as $planting)
                        <div class="p-3 bg-green-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-900">{{ $planting->crop->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600">{{ $planting->field->name ?? 'N/A' }} - {{ $planting->planting_date->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(isset($upcomingHarvests) && $upcomingHarvests->count() > 0)
        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-harvest text-orange-600 mr-2"></i> Récoltes à venir (7 jours)
                    </h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                        {{ $upcomingHarvests->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($upcomingHarvests->take(5) as $harvest)
                        <div class="p-3 bg-orange-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-900">{{ $harvest->crop->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600">{{ $harvest->field->name ?? 'N/A' }} - {{ $harvest->expected_harvest_date->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Contenu principal en 2 colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Récoltes récentes -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Récoltes récentes</h2>
                    <a href="{{ route('harvests.index') }}" class="text-sm text-green-600 hover:text-green-700">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentHarvests->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentHarvests as $harvest)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $harvest->crop->name ?? 'Récolte' }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($harvest->field)
                                            {{ $harvest->field->name }}
                                        @endif
                                        @if($harvest->quantity)
                                            • {{ number_format($harvest->quantity, 2) }} {{ $harvest->unit }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        @if($harvest->date)
                                            {{ \Carbon\Carbon::parse($harvest->date)->format('d/m/Y') }}
                                        @else
                                            {{ $harvest->created_at->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($harvest->status === 'COMPLETED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Complétée
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Aucune récolte récente</p>
                @endif
            </div>
        </div>

        <!-- Tâches récentes -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Tâches récentes</h2>
                    <a href="{{ route('farm-tasks.index') }}" class="text-sm text-green-600 hover:text-green-700">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTasks as $task)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $task->title }}</p>
                                    <p class="text-sm text-gray-600">
                                        Assigné à: {{ $task->assignedTo->name ?? 'Non assigné' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="ml-4">
                                    @if($task->status === 'COMPLETED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @elseif($task->status === 'IN_PROGRESS')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-spinner"></i>
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Aucune tâche récente</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Paiements récents -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Paiements récents</h2>
                <a href="{{ route('payments.index') }}" class="text-sm text-green-600 hover:text-green-700">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $payment->type === 'INCOME' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $payment->type === 'INCOME' ? 'Revenu' : 'Dépense' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $payment->type === 'INCOME' ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->customer->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'COMPLETED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Complété
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Aucun paiement récent
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

