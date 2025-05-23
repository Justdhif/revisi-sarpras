<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemUnit;
use App\Models\ActivityLog;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        $user = User::where('email', $request->email)->first();

        if ($user && $user->role === 'admin' && Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            return redirect()->route('dashboard')->with('success', 'Hai ' . $user->username . ', selamat datang!');
        }

        if ($user && $user->role === 'user') {
            return redirect()->back()->with('error', 'Akun mu bukan sebagai admin');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * Logout user dari sistem.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    /**
     * Menampilkan data statistik dan ringkasan pada dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Statistik utama
        $totalUsers = User::where('role', 'user')->count();
        $totalItems = Item::count();
        $totalBorrows = BorrowRequest::count();
        $totalReturns = ReturnRequest::count();

        // Data terbaru untuk ditampilkan
        $recentLogs = ActivityLog::latest()->take(5)->get();
        $recentBorrows = BorrowRequest::latest()->take(5)->get();
        $recentReturns = ReturnRequest::latest()->take(5)->get();
        $recentItems = Item::latest()->take(5)->get();
        $recentItemUnits = ItemUnit::latest()->take(5)->get();
        $sku = ItemUnit::pluck('sku');

        return view('dashboard', compact(
            'totalUsers',
            'totalItems',
            'totalBorrows',
            'totalReturns',
            'recentLogs',
            'recentBorrows',
            'recentReturns',
            'recentItems',
            'recentItemUnits',
            'sku'
        ));
    }
}
