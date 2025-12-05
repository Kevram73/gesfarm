@extends('layouts.dashboard')

@section('title', 'Tâches')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tâches</h1>
            <p class="text-gray-600 mt-1">Gérez vos tâches agricoles</p>
        </div>
        <a href="{{ route('farm-tasks.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-plus mr-2"></i> Nouvelle tâche
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('farm-tasks.index') }}" class="flex items-center space-x-4 flex-wrap gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>En attente</option>
                <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>En cours</option>
                <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Complétée</option>
                <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Annulée</option>
            </select>
            <select name="priority" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Toutes les priorités</option>
                <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>Basse</option>
                <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>Moyenne</option>
                <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>Haute</option>
                <option value="URGENT" {{ request('priority') == 'URGENT' ? 'selected' : '' }}>Urgente</option>
            </select>
            <select name="assigned_to_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                <option value="">Tous les assignés</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_to_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Liste des tâches -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'échéance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                @if($task->description)
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($task->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($task->type)
                                    {{ ucfirst(strtolower(str_replace('_', ' ', $task->type))) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->priority === 'URGENT')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Urgente</span>
                                @elseif($task->priority === 'HIGH')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Haute</span>
                                @elseif($task->priority === 'MEDIUM')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Moyenne</span>
                                @elseif($task->priority === 'LOW')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Basse</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $task->assignedTo->name ?? ($task->employee->name ?? 'Non assigné') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($task->due_date)
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'COMPLETED')
                                        <span class="ml-2 text-red-600"><i class="fas fa-exclamation-circle"></i></span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->status === 'COMPLETED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Complétée</span>
                                @elseif($task->status === 'IN_PROGRESS')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">En cours</span>
                                @elseif($task->status === 'PENDING')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                @elseif($task->status === 'CANCELLED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Annulée</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('farm-tasks.edit', $task->id) }}" class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('farm-tasks.destroy', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
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
                                <i class="fas fa-tasks text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Aucune tâche enregistrée</p>
                                <p class="text-sm mt-2">Commencez par créer votre première tâche</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection



