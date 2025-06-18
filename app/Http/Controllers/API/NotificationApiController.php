<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = Notification::with([
            'borrowRequest.borrowDetail.itemUnit.item',
            'returnRequest.details.itemUnit.item'
        ])
            ->where('receiver_id', $userId)
            ->when($request->search, function ($query) use ($request) {
                $search = '%' . $request->search . '%';
                return $query->where('message', 'like', $search)
                    ->orWhere('notification_type', 'like', $search);
            })
            ->when($request->type, fn($q) => $q->where('notification_type', $request->type))
            ->when($request->status, fn($q) => $q->where('is_read', $request->status === 'read'))
            ->orderBy($request->get('sort', 'created_at'), $request->get('direction', 'desc'))->get();

        return response()->json($notifications);
    }

    public function show(Request $request, $id)
    {
        $notification = Notification::with([
            'borrowRequest.borrowDetail.itemUnit.item',
            'returnRequest.details.itemUnit.item'
        ])
            ->where('receiver_id', $request->user()->id)
            ->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json($notification);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('receiver_id', $request->user()->id)
            ->findOrFail($id);

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notifikasi ditandai sebagai dibaca.']);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Semua notifikasi ditandai sebagai dibaca.']);
    }
}
