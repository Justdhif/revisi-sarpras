<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ManualNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notifications();

        if ($request->status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($request->status === 'unread') {
            $query->whereNull('read_at');
        }

        if ($request->search) {
            $query->where('data->title', 'like', '%' . $request->search . '%');
        }

        $notifications = $query->latest()->get();

        $users = User::where('role', 'user')->get();

        if ($request->ajax()) {
            return response()->json(
                $notifications->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['title'] ?? 'Notifikasi',
                        'message' => $n->data['message'] ?? '-',
                        'created_at' => $n->created_at->format('d M Y H:i'),
                        'read_at' => $n->read_at,
                    ];
                })
            );
        }

        return view('notifications.index', compact('notifications', 'users'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notifikasi',
            'message' => $notification->data['message'] ?? '-',
            'created_at' => $notification->created_at->format('d M Y H:i'),
            'related_type' => null,
            'related_id' => null,
            'related_url' => null,
            'details' => [],
            'user' => null,
        ];

        // Jika notifikasi terkait peminjaman
        if (isset($notification->data['borrow_request_id'])) {
            $borrow = BorrowRequest::with(['borrowDetail.itemUnit.item', 'user'])->find($notification->data['borrow_request_id']);

            if ($borrow) {
                $data['related_type'] = 'Peminjaman';
                $data['related_id'] = $borrow->id;
                $data['related_url'] = route('borrow-requests.show', $borrow->id);

                // Detail item
                $data['details'] = $borrow->borrowDetail->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'name' => $detail->itemUnit->item->name ?? '-',
                        'image_url' => $detail->itemUnit->item->image_url ?? null,
                        'quantity' => $detail->quantity,
                        
                    ];
                });

                // User
                $data['user'] = [
                    'name' => $borrow->user->name,
                    'email' => $borrow->user->email,
                    'phone' => $borrow->user->phone,
                    'profile_picture' => $borrow->user->profile_picture ?? '/default-avatar.png',
                ];
            }
        }

        // Jika notifikasi terkait pengembalian
        if (isset($notification->data['return_request_id'])) {
            $return = ReturnRequest::with(['returnDetails.itemUnit.item', 'user'])->find($notification->data['return_request_id']);

            if ($return) {
                $data['related_type'] = 'Pengembalian';
                $data['related_id'] = $return->id;
                $data['related_url'] = route('return_requests.show', $return->id);

                // Detail item
                $data['details'] = $return->returnDetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'name' => $detail->itemUnit->item->name ?? '-',
                        'image_url' => $detail->itemUnit->item->image_url ?? null,
                    ];
                });

                // User
                $data['user'] = [
                    'name' => $return->user->name,
                    'email' => $return->user->email,
                    'phone' => $return->user->phone,
                    'profile_picture' => $return->user->profile_picture ?? '/default-avatar.png',
                ];
            }
        }

        return response()->json($data);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        $users = User::where('active', true)->get();
        return view('notifications.send', compact('users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'message' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'message' => $request->message,
        ];

        if ($request->user_id === 'all') {
            $users = User::where('active', true)->get();
        } else {
            $users = User::where('id', $request->user_id)->get();
        }

        Notification::send($users, new ManualNotification($data));

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil dikirim.');
    }

    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }
}
