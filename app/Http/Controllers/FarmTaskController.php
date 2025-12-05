<?php

namespace App\Http\Controllers;

use App\Models\FarmTask;
use App\Models\Employee;
use App\Models\User;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = FarmTask::where('farm_id', $farmId)
            ->with(['assignedTo', 'completedBy', 'createdBy', 'employee']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('assigned_to_id')) {
            $query->where('assigned_to_id', $request->assigned_to_id);
        }
        
        $tasks = $query->orderBy('due_date', 'asc')->paginate(15);
        
        $employees = Employee::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->with('user')
            ->get();
        
        $users = User::where('farm_id', $farmId)
            ->where('is_active', true)
            ->get();
        
        return view('equipements.taches.index', compact('tasks', 'employees', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $employees = Employee::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->with('user')
            ->get();
        
        $users = User::where('farm_id', $farmId)
            ->where('is_active', true)
            ->get();
        
        return view('equipements.taches.create', compact('employees', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:PLANTING,HARVESTING,IRRIGATION,FERTILIZATION,PEST_CONTROL,MAINTENANCE,OTHER',
            'priority' => 'nullable|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'nullable|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'due_date' => 'nullable|date',
            'assigned_to_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|integer',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'PENDING';
        $validated['priority'] = $validated['priority'] ?? 'MEDIUM';

        FarmTask::create($validated);

        return redirect()->route('farm-tasks.index')
            ->with('success', 'Tâche créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $task = FarmTask::where('farm_id', $farmId)->findOrFail($id);
        
        $employees = Employee::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->with('user')
            ->get();
        
        $users = User::where('farm_id', $farmId)
            ->where('is_active', true)
            ->get();
        
        return view('equipements.taches.edit', compact('task', 'employees', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $task = FarmTask::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:PLANTING,HARVESTING,IRRIGATION,FERTILIZATION,PEST_CONTROL,MAINTENANCE,OTHER',
            'priority' => 'nullable|in:LOW,MEDIUM,HIGH,URGENT',
            'status' => 'nullable|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'due_date' => 'nullable|date',
            'assigned_to_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'related_type' => 'nullable|string',
            'related_id' => 'nullable|integer',
        ]);

        // Si le statut passe à COMPLETED, enregistrer completed_by et completed_at
        if ($validated['status'] === 'COMPLETED' && $task->status !== 'COMPLETED') {
            $validated['completed_by_id'] = Auth::id();
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return redirect()->route('farm-tasks.index')
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $task = FarmTask::where('farm_id', $farmId)->findOrFail($id);
        $task->delete();

        return redirect()->route('farm-tasks.index')
            ->with('success', 'Tâche supprimée avec succès.');
    }
}
