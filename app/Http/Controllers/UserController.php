<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Origin;
use Barryvdh\DomPDF\PDF;
use App\Exports\UsersExport;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    const PAGINATION_COUNT = 10;
    const EXPORT_FILENAME = 'data-users';
    const PROFILE_IMAGE_PATH = 'profile_pictures';
    const MAX_IMAGE_SIZE = 2048; // in KB
    const DEFAULT_AVATAR_URL = 'https://ui-avatars.com/api/';

    public function exportExcel()
    {
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new UsersExport, $filename);
    }

    public function exportPdf()
    {
        $users = User::where('role', 'user')->latest()->get();
        $filename = self::EXPORT_FILENAME . '-' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('users.pdf', compact('users'));
        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'username');
        $sortDirection = $request->get('direction', 'asc');

        $query = User::query()
            ->when($request->search, function ($query) use ($request) {
                return $query->where('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->when($request->origin, function ($query) use ($request) {
                return $query->where('origin_id', $request->origin);
            })
            ->when($request->status, function ($query) use ($request) {
                if ($request->status === 'active') {
                    return $query->where('active', true);
                } elseif ($request->status === 'inactive') {
                    return $query->where('active', false);
                }
            })
            ->orderBy($sortField, $sortDirection);

        $users = $query->paginate(10);
        $origins = Origin::latest()->get();

        return view('users.index', [
            'users' => $users,
            'origins' => $origins,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        $classes = Origin::latest()->get();
        return view('users.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'origin_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'origin_id' => $validated['origin_id'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'profile_picture' => self::DEFAULT_AVATAR_URL . '?name=' . urlencode($validated['name']) . '&background=random&rounded=true',
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        $stats = [
            'totalBorrowCount' => BorrowRequest::where('user_id', $user->id)->count(),
            'totalItemBorrowed' => BorrowDetail::whereHas('borrowRequest', fn($q) => $q->where('user_id', $user->id))->count(),
            'totalItemReturned' => BorrowDetail::whereHas('borrowRequest', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->whereHas('returnRequest', function ($q) {
                        $q->where('status', 'approved');
                    });
            })->count(),
        ];

        $borrows = [
            'activeBorrows' => BorrowRequest::with(['borrowDetail.itemUnit.item', 'returnRequest'])
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->get(),

            'returnedBorrows' => BorrowRequest::with(['borrowDetail.itemUnit.item', 'returnRequest'])
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereHas('returnRequest', function ($q) {
                    $q->where('status', 'approved');
                })
                ->get(),
        ];

        return view('users.show', array_merge(
            ['user' => $user],
            $stats,
            $borrows
        ));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'origin_id' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . self::MAX_IMAGE_SIZE,
        ]);

        if (isset($validated['profile_picture'])) {
            $imagePath = $validated['profile_picture']->store(self::PROFILE_IMAGE_PATH, 'public');
            $validated['profile_picture'] = 'storage/' . $imagePath;
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User dihapus.');
    }
}
