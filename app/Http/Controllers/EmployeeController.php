<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Employee::where('farm_id', $farmId)->with('user');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $employees = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('personnel.employes.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        // Récupérer les utilisateurs disponibles (sans employé associé)
        $users = User::where('farm_id', $farmId)
            ->whereDoesntHave('employee')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('personnel.employes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'nullable|in:ACTIVE,INACTIVE,TERMINATED',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['status'] = $validated['status'] ?? 'ACTIVE';

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $employee = Employee::where('farm_id', $farmId)->findOrFail($id);
        
        $users = User::where('farm_id', $farmId)
            ->where(function($q) use ($employee) {
                $q->whereDoesntHave('employee')
                  ->orWhereHas('employee', function($q2) use ($employee) {
                      $q2->where('id', $employee->id);
                  });
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('personnel.employes.edit', compact('employee', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $employee = Employee::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'nullable|in:ACTIVE,INACTIVE,TERMINATED',
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $employee = Employee::where('farm_id', $farmId)->findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employé supprimé avec succès.');
    }
}
