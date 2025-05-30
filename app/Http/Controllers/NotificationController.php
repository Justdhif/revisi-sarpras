<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $selected = null;
        $notifications = $request->user()->notifications()->latest()->get();

        if ($request->has('id')) {
            $selected = $request->user()->notifications()->findOrFail($request->id);

            // Mark as read when viewing directly
            if (!$selected->is_read) {
                $selected->update(['is_read' => true]);
            }
        }

        if ($request->ajax) {
            return response()->json([
                'detail' => view('notifications.partials.detail', ['selected' => $selected])->render()
            ]);
        }

        return view('notifications.index', [
            'notifications' => $notifications,
            'selected' => $selected,
            'unreadCount' => $request->user()->unreadNotifications()->count()
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->user()->notifications->latest();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('data->title', 'like', $searchTerm)
                    ->orWhere('data->body', 'like', $searchTerm);
            });
        }

        $notifications = $query->take(50)->get();

        return view('notifications.partials.list', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function unreadCount(Request $request)
    {
        return response()->json([
            'count' => $request->user()->unreadNotifications()->count()
        ]);
    }
}
