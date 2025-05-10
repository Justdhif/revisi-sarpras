<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\ItemUnit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // Dashboard
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalBorrows = BorrowRequest::count();
        $totalReturns = ReturnRequest::count();
        $recentLogs = ActivityLog::latest()->take(5)->get();
        $recentBorrows = BorrowRequest::latest()->take(5)->get();
        $recentReturns = ReturnRequest::latest()->take(5)->get();
        $recentItems = Item::latest()->take(5)->get();
        $recentItemUnits = ItemUnit::latest()->take(5)->get();
        $sku = ItemUnit::pluck('sku');

        $borrowStats = DB::table('borrow_requests')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $returnStats = DB::table('return_requests')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Buat array lengkap dari Januari sampai Desember
        $months = range(1, 12);
        $borrowData = [];
        $returnData = [];
        $labels = [];

        foreach ($months as $m) {
            $labels[] = Carbon::create()->month($m)->format('M');
            $borrowData[] = $borrowStats[$m] ?? 0;
            $returnData[] = $returnStats[$m] ?? 0;
        }

        return view('dashboard', compact('totalUsers', 'totalItems', 'totalBorrows', 'totalReturns', 'recentLogs', 'recentBorrows', 'recentReturns', 'recentItems', 'recentItemUnits', 'sku'), [
            'chartLabels' => $labels,
            'chartBorrowData' => $borrowData,
            'chartReturnData' => $returnData,
        ]);
    }
}
