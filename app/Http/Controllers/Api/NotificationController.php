<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function getNotifications(Request $request): JsonResponse
    {
        $query = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function getUnreadNotifications(): JsonResponse
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue',
            'data' => $notification
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', auth()->id())
            ->where('status', 'unread')
            ->update([
                'status' => 'read',
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ]);
    }

    public function createNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'user_id' => 'nullable|exists:users,id',
            'related_entity_id' => 'nullable|integer',
            'related_entity_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = Notification::create([
            ...$request->validated(),
            'user_id' => $request->user_id ?? auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification créée avec succès',
            'data' => $notification
        ], 201);
    }

    public function deleteNotification(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée avec succès'
        ]);
    }
}