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
    /**
     * Menyediakan fungsionalitas untuk mengekspor data user ke file Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'data-users.xlsx');
    }

    /**
     * Menyediakan fungsionalitas untuk mengekspor data user ke file PDF.
     */
    public function exportPdf()
    {
        // Mengambil data user dengan role 'user'
        $users = User::where('role', 'user')->latest()->get();

        // Membuat PDF dengan view 'users.pdf'
        $pdf = Pdf::loadView('users.pdf', compact('users'));

        // Mengunduh file PDF yang dihasilkan
        return $pdf->download('data-users.pdf');
    }

    /**
     * Menampilkan daftar user.
     */
    public function index()
    {
        // Mengambil seluruh data user untuk ditampilkan
        $users = User::all();

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan data user baru yang dikirimkan melalui form.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        // Validasi inputan dari form
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ]);

        // Membuat user baru
        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user'
        ]);

        // Mengarahkan ke halaman daftar user dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Menampilkan detail user beserta statistik peminjaman.
     *
     * @param  User  $user
     */
    public function show(User $user)
    {
        // Menghitung jumlah peminjaman yang dilakukan oleh user
        $totalBorrowCount = BorrowRequest::where('user_id', $user->id)->count();

        // Menghitung jumlah item yang dipinjam oleh user
        $totalItemBorrowed = BorrowDetail::whereHas('borrowRequest', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Menghitung jumlah item yang sudah dikembalikan oleh user
        $totalItemReturned = BorrowDetail::whereHas('borrowRequest', function ($q) use ($user) {
            $q->where('user_id', $user->id)->whereHas('returnRequest');
        })->count();

        // Menampilkan semua peminjaman aktif yang belum dikembalikan
        $activeBorrows = BorrowRequest::with('borrowDetail.itemUnit.item')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDoesntHave('returnRequest') // Belum dikembalikan
            ->get();

        // Menampilkan semua peminjaman yang sudah dikembalikan
        $returnedBorrows = BorrowRequest::with('details.itemUnit.item')
            ->where('user_id', $user->id)
            ->whereHas('returnRequest') // Sudah dikembalikan
            ->get();

        // Menampilkan halaman detail user beserta data peminjaman terkait
        return view('users.show', compact('user', 'activeBorrows', 'returnedBorrows', 'totalBorrowCount', 'totalItemBorrowed', 'totalItemReturned'));
    }

    /**
     * Menampilkan form untuk mengedit data user.
     *
     * @param  User  $user
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user yang ada dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     */
    public function update(Request $request, User $user)
    {
        // Validasi inputan dari form
        $validated = $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'password' => 'nullable|confirmed|min:6',
        ]);

        // Memperbarui data user
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Mengarahkan ke halaman daftar user dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User diperbarui.');
    }

    /**
     * Menghapus data user dari database.
     *
     * @param  User  $user
     */
    public function destroy(User $user)
    {
        // Menghapus user dari database
        $user->delete();

        // Mengarahkan ke halaman daftar user dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User dihapus.');
    }
}
