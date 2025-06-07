<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;

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
        $users = $this->getFilteredUsers($request);

        if ($request->ajax()) {
            return $this->getAjaxResponse($users);
        }

        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateUserRequest($request);
        $this->createUser($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        $stats = $this->getUserBorrowStats($user);
        $borrows = $this->getUserBorrows($user);

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
        $validated = $this->validateUserUpdateRequest($request, $user);
        $this->updateUser($user, $validated);

        return redirect()->route('users.index')
            ->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User dihapus.');
    }

    private function getFilteredUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('origin', 'like', "%{$request->search}%");
            });
        }

        return $query->orderBy('username')->paginate(self::PAGINATION_COUNT);
    }

    private function getAjaxResponse($users)
    {
        return response()->json([
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'total' => $users->total(),
            'links' => $users->links()->elements,
        ]);
    }

    private function validateUserRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'origin' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
    }

    private function createUser(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'origin' => $data['origin'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'profile_picture' => $this->generateAvatarUrl($data['name']),
        ]);
    }

    private function validateUserUpdateRequest(Request $request, User $user)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'origin' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . self::MAX_IMAGE_SIZE,
        ]);
    }

    private function updateUser(User $user, array $data)
    {
        if (isset($data['profile_picture'])) {
            $data['profile_picture'] = $this->storeProfileImage($data['profile_picture']);
        }

        $user->update($data);
    }

    private function storeProfileImage($image)
    {
        $imagePath = $image->store(self::PROFILE_IMAGE_PATH, 'public');
        return 'storage/' . $imagePath;
    }

    private function generateAvatarUrl(string $name)
    {
        return self::DEFAULT_AVATAR_URL . '?name=' . urlencode($name) . '&background=random&rounded=true';
    }

    private function getUserBorrowStats(User $user)
    {
        return [
            'totalBorrowCount' => BorrowRequest::where('user_id', $user->id)->count(),
            'totalItemBorrowed' => BorrowDetail::whereHas('borrowRequest', fn($q) => $q->where('user_id', $user->id))->count(),
            'totalItemReturned' => BorrowDetail::whereHas('borrowRequest', fn($q) => $q->where('user_id', $user->id)->whereHas('returnRequest'))->count(),
        ];
    }

    private function getUserBorrows(User $user)
    {
        return [
            'activeBorrows' => BorrowRequest::with('borrowDetail.itemUnit.item')
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDoesntHave('returnRequest')
                ->get(),
            'returnedBorrows' => BorrowRequest::with('details.itemUnit.item')
                ->where('user_id', $user->id)
                ->whereHas('returnRequest')
                ->get(),
        ];
    }
}
