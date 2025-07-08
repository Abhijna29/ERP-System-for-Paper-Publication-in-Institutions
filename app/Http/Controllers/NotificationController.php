<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Auth::user()->unreadNotifications;
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($notification) {
                // Log::info($notification->data);
                $data = (array) $notification->data;
                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'No title',
                    'message' => $data['message'] ?? 'No message',
                    'paper_id' => $data['paper_id'] ?? null,
                    'paper_title' => $data['paper_title'] ?? null,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'role' => Auth::user()->role,
                    'link' => $data['link'] ?? '#'
                ];
            }),
        ]);
    }

    public function markAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function markSingleAsRead($id)
    {
        $notification = Auth::user()->unreadNotifications->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
}
