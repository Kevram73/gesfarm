@extends('layouts.dashboard')

@section('title', 'Nouvelle tâche')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nouvelle tâche</h1>
        <p class="text-gray-600 mt-1">Créez une nouvelle tâche agricole</p>
    </div>

    <form method="POST" action="{{ route('farm-tasks.store') }}" class="bg-white rounded-lg shadow-md overflow-hidden">
        @csrf

        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-gray-400"></i> Titre *
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-400"></i> Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i> Type
                    </label>
                    <select id="type" name="type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="PLANTING" {{ old('type') == 'PLANTING' ? 'selected' : '' }}>Plantation</option>
                        <option value="HARVESTING" {{ old('type') == 'HARVESTING' ? 'selected' : '' }}>Récolte</option>
                        <option value="IRRIGATION" {{ old('type') == 'IRRIGATION' ? 'selected' : '' }}>Irrigation</option>
                        <option value="FERTILIZATION" {{ old('type') == 'FERTILIZATION' ? 'selected' : '' }}>Fertilisation</option>
                        <option value="PEST_CONTROL" {{ old('type') == 'PEST_CONTROL' ? 'selected' : '' }}>Lutte contre les ravageurs</option>
                        <option value="MAINTENANCE" {{ old('type') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                        <option value="OTHER" {{ old('type') == 'OTHER' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-gray-400"></i> Priorité
                    </label>
                    <select id="priority" name="priority"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="LOW" {{ old('priority', 'MEDIUM') == 'LOW' ? 'selected' : '' }}>Basse</option>
                        <option value="MEDIUM" {{ old('priority', 'MEDIUM') == 'MEDIUM' ? 'selected' : '' }}>Moyenne</option>
                        <option value="HIGH" {{ old('priority') == 'HIGH' ? 'selected' : '' }}>Haute</option>
                        <option value="URGENT" {{ old('priority') == 'URGENT' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i> Statut
                    </label>
                    <select id="status" name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="PENDING" {{ old('status', 'PENDING') == 'PENDING' ? 'selected' : '' }}>En attente</option>
                        <option value="IN_PROGRESS" {{ old('status') == 'IN_PROGRESS' ? 'selected' : '' }}>En cours</option>
                        <option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>Complétée</option>
                        <option value="CANCELLED" {{ old('status') == 'CANCELLED' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i> Date d'échéance
                    </label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="assigned_to_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i> Assigné à (Utilisateur)
                    </label>
                    <select id="assigned_to_id" name="assigned_to_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Non assigné</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2 text-gray-400"></i> Assigné à (Employé)
                    </label>
                    <select id="employee_id" name="employee_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Non assigné</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                                @if($employee->user)
                                    ({{ $employee->user->email }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
            <a href="{{ route('farm-tasks.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-save mr-2"></i> Créer la tâche
            </button>
        </div>
    </form>
</div>
@endsection



