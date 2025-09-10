<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group User Management
 * 
 * APIs for managing users and roles
 */
class UserController extends Controller
{
    /**
     * Get Users
     * 
     * Retrieve a paginated list of users
     * 
     * @queryParam search string Search in name and email
     * @queryParam role string Filter by role
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "users": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(User::class)
            ->with('roles')
            ->allowedSorts(['name', 'email', 'created_at']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total()
                ]
            ]
        ]);
    }

    /**
     * Create User
     * 
     * Create a new user
     * 
     * @bodyParam name string required User name
     * @bodyParam email string required User email
     * @bodyParam password string required User password
     * @bodyParam roles array Array of role names
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "User created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user->load('roles')
        ], 201);
    }

    /**
     * Get User Details
     * 
     * Retrieve a specific user
     * 
     * @urlParam id integer required User ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $user = User::with(['roles', 'permissions'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Update User
     * 
     * Update an existing user
     * 
     * @urlParam id integer required User ID
     * @bodyParam name string User name
     * @bodyParam email string User email
     * @bodyParam password string New password
     * @bodyParam roles array Array of role names
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "User updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only(['name', 'email']);
        
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user->load('roles')
        ]);
    }

    /**
     * Delete User
     * 
     * Delete a user
     * 
     * @urlParam id integer required User ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "User deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the last admin
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete the last admin user'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get Available Roles
     * 
     * Get all available roles
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": [...]
     * }
     */
    public function roles()
    {
        $roles = \Spatie\Permission\Models\Role::all();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    /**
     * Get Available Permissions
     * 
     * Get all available permissions
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": [...]
     * }
     */
    public function permissions()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }
}
