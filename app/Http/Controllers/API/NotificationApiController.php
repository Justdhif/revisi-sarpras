<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    // GET /api/notifications
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->notifications();

        if ($request->status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($request->status === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notifikasi',
                'message' => $n->data['message'] ?? '-',
                'time' => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->format('Y-m-d H:i:s'),
                'read_at' => $n->read_at,
                'user_name' => $n->data['user_name'] ?? null,
                'related_type' => $n->data['borrow_request_id'] ? 'Peminjaman' : ($n->data['return_request_id'] ? 'Pengembalian' : null),
                'related_id' => $n->data['borrow_request_id'] ?? $n->data['return_request_id'] ?? null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ]);
    }

    // GET /api/notifications/{id}
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notifikasi',
            'message' => $notification->data['message'] ?? '-',
            'created_at' => $notification->created_at->format('Y-m-d H:i'),
            'read_at' => $notification->read_at,
            'user_name' => $notification->data['user_name'] ?? null,
            'related_type' => isset($notification->data['borrow_request_id']) ? 'Peminjaman' : (isset($notification->data['return_request_id']) ? 'Pengembalian' : null),
            'related_id' => $notification->data['borrow_request_id'] ?? $notification->data['return_request_id'] ?? null,
        ]);
    }

    // POST /api/notifications/mark-all-as-read
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success', 'message' => 'Semua notifikasi telah dibaca.']);
    }
}
