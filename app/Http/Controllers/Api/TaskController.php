<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Task Management
 * 
 * APIs for managing tasks and work assignments
 */
class TaskController extends Controller
{
    /**
     * Get Tasks
     * 
     * Retrieve a paginated list of tasks
     * 
     * @queryParam type string Filter by task type (agricultural, livestock, maintenance, administrative)
     * @queryParam status string Filter by status (pending, in_progress, completed, cancelled)
     * @queryParam priority string Filter by priority (low, medium, high, urgent)
     * @queryParam assigned_to integer Filter by assigned user ID
     * @queryParam zone_id integer Filter by zone ID
     * @queryParam search string Search in title and description
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "tasks": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Task::class)
            ->with(['assignedTo', 'createdBy', 'zone'])
            ->allowedFilters(['type', 'status', 'priority', 'assigned_to', 'zone_id'])
            ->allowedSorts(['title', 'due_date', 'priority', 'created_at']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter overdue tasks
        if ($request->has('overdue') && $request->boolean('overdue')) {
            $query->where('status', '!=', 'completed')
                  ->where('due_date', '<', now());
        }

        $tasks = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total()
                ]
            ]
        ]);
    }

    /**
     * Create Task
     * 
     * Create a new task
     * 
     * @bodyParam title string required Task title
     * @bodyParam description string required Task description
     * @bodyParam type string required Task type (agricultural, livestock, maintenance, administrative)
     * @bodyParam priority string Task priority (low, medium, high, urgent)
     * @bodyParam due_date date required Due date
     * @bodyParam assigned_to integer required Assigned user ID
     * @bodyParam zone_id integer Zone ID
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Task created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:agricultural,livestock,maintenance,administrative',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'required|exists:users,id',
            'zone_id' => 'nullable|exists:zones,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id;

        $task = Task::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => $task->load(['assignedTo', 'createdBy', 'zone'])
        ], 201);
    }

    /**
     * Get Task Details
     * 
     * Retrieve a specific task
     * 
     * @urlParam id integer required Task ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $task = Task::with(['assignedTo', 'createdBy', 'zone'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Update Task
     * 
     * Update an existing task
     * 
     * @urlParam id integer required Task ID
     * @bodyParam title string Task title
     * @bodyParam description string Task description
     * @bodyParam type string Task type
     * @bodyParam priority string Task priority
     * @bodyParam status string Task status
     * @bodyParam due_date date Due date
     * @bodyParam assigned_to integer Assigned user ID
     * @bodyParam zone_id integer Zone ID
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Task updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:agricultural,livestock,maintenance,administrative',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'due_date' => 'sometimes|date',
            'assigned_to' => 'sometimes|exists:users,id',
            'zone_id' => 'nullable|exists:zones,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Set completed date if status is being changed to completed
        if ($request->has('status') && $request->status === 'completed' && $task->status !== 'completed') {
            $request->merge(['completed_date' => now()]);
        }

        $task->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => $task->load(['assignedTo', 'createdBy', 'zone'])
        ]);
    }

    /**
     * Delete Task
     * 
     * Delete a task
     * 
     * @urlParam id integer required Task ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Task deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Get My Tasks
     * 
     * Get tasks assigned to the authenticated user
     * 
     * @queryParam status string Filter by status
     * @queryParam type string Filter by type
     * @queryParam overdue boolean Filter overdue tasks
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "tasks": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function myTasks(Request $request)
    {
        $query = QueryBuilder::for(Task::class)
            ->where('assigned_to', $request->user()->id)
            ->with(['createdBy', 'zone'])
            ->allowedFilters(['status', 'type', 'priority'])
            ->allowedSorts(['due_date', 'priority', 'created_at']);

        if ($request->has('overdue') && $request->boolean('overdue')) {
            $query->where('status', '!=', 'completed')
                  ->where('due_date', '<', now());
        }

        $tasks = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total()
                ]
            ]
        ]);
    }

    /**
     * Complete Task
     * 
     * Mark a task as completed
     * 
     * @urlParam id integer required Task ID
     * @bodyParam notes string Completion notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Task completed successfully",
     *   "data": {...}
     * }
     */
    public function complete(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Check if user is assigned to this task or has permission
        if ($task->assigned_to !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to complete this task'
            ], 403);
        }

        $task->update([
            'status' => 'completed',
            'completed_date' => now(),
            'notes' => $request->notes ?? $task->notes
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task completed successfully',
            'data' => $task->load(['assignedTo', 'createdBy', 'zone'])
        ]);
    }
}
