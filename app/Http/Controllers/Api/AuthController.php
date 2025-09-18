<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @group Authentication
 * 
 * APIs for user authentication and management
 */
class AuthController extends Controller
{
    /**
     * Login
     * 
     * Authenticate user and return access token
     * 
     * @bodyParam email string required User email address
     * @bodyParam password string required User password
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Login successful",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "roles": ["admin"]
     *     },
     *     "token": "1|abc123..."
     *   }
     * }
     * 
     * @response 401 {
     *   "status": "error",
     *   "message": "Invalid credentials"
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames()
                ],
                'token' => $token
            ]
        ]);
    }

    /**
     * Logout
     * 
     * Revoke the current access token
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Logout successful"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get User Profile
     * 
     * Get the authenticated user's profile information
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "roles": ["admin"],
     *       "permissions": ["create-users", "view-stock"]
     *     }
     *   }
     * }
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'role' => $user->getRoleNames()->first() ?? 'user',
                    'roles' => $user->getRoleNames(),
                    'is_active' => $user->is_active ?? true,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'permissions' => $user->getAllPermissions()->pluck('name')
                ]
            ]
        ]);
    }
}
