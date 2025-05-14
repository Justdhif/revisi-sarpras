<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'data-users.xlsx');
    }

    public function exportPdf()
    {
        $users = User::where('role', 'user')->latest()->get();
        $pdf = Pdf::loadView('users.pdf', compact('users'));
        return $pdf->download('data-users.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user'
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $totalBorrowCount = BorrowRequest::where('user_id', $user->id)->count();

        $totalItemBorrowed = BorrowDetail::whereHas('borrowRequest', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $totalItemReturned = BorrowDetail::whereHas('borrowRequest', function ($q) use ($user) {
            $q->where('user_id', $user->id)->whereHas('returnRequest');
        })->count();

        $activeBorrows = BorrowRequest::with('borrowDetail.itemUnit.item')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDoesntHave('returnRequest') // belum dikembalikan
            ->get();

        $returnedBorrows = BorrowRequest::with('details.itemUnit.item')
            ->where('user_id', $user->id)
            ->whereHas('returnRequest') // sudah dikembalikan
            ->get();

        return view('users.show', compact('user', 'activeBorrows', 'returnedBorrows', 'totalBorrowCount', 'totalItemBorrowed', 'totalItemReturned'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User dihapus.');
    }
}
