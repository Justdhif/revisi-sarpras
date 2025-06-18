<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $query = Notification::query()
            ->with(['borrowRequest.borrowDetail.itemUnit.item', 'returnRequest.details.itemUnit.item'])
            ->where('receiver_id', $userId) // Filter utama: hanya notifikasi user ini
            ->when($request->search, function ($query) use ($request) {
                $search = '%' . $request->search . '%';
                // Group kondisi search DALAM scope receiver_id
                return $query->where(function ($q) use ($search) {
                    $q->where('message', 'like', $search)
                        ->orWhere('notification_type', 'like', $search);
                });
            })
            ->when($request->type, function ($query) use ($request) {
                return $query->where('notification_type', $request->type);
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('is_read', $request->status === 'read');
            })
            ->orderBy($sortField, $sortDirection);

        $notifications = $query->paginate(10);

        $selected = $request->has('selected')
            ? Notification::find($request->selected)
            : null;

        return view('notifications.index', [
            'notifications' => $notifications,
            'selected' => $selected,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read');
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('receiver_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect()->route('notifications.index')->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }
}
