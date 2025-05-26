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
use Illuminate\Support\Facades\Cache;

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
        // Statistik utama dengan trend
        $totalUsers = User::where('role', 'user')->count();
        $totalUsersTrend = $this->calculateTrend(User::class, 'user');

        $totalItems = Item::count();
        $totalItemsTrend = $this->calculateTrend(Item::class);

        $totalBorrows = BorrowRequest::count();
        $totalBorrowsTrend = $this->calculateTrend(BorrowRequest::class);

        $totalReturns = ReturnRequest::count();
        $totalReturnsTrend = $this->calculateTrend(ReturnRequest::class);

        // Data terbaru untuk ditampilkan
        $recentLogs = ActivityLog::latest()->take(5)->get();
        $recentBorrows = BorrowRequest::with('user')->latest()->take(5)->get();
        $recentReturns = ReturnRequest::with('borrowRequest.user')->latest()->take(5)->get();
        $recentItems = Item::with('category')->latest()->take(5)->get();
        $recentItemUnits = ItemUnit::with('item.category')->latest()->take(5)->get();
        $sku = ItemUnit::pluck('sku');

        return view('dashboard', compact(
            'totalUsers',
            'totalUsersTrend',
            'totalItems',
            'totalItemsTrend',
            'totalBorrows',
            'totalBorrowsTrend',
            'totalReturns',
            'totalReturnsTrend',
            'recentLogs',
            'recentBorrows',
            'recentReturns',
            'recentItems',
            'recentItemUnits',
            'sku'
        ));
    }

    /**
     * Menghitung persentase trend perubahan data
     *
     * @param string $model Class model yang akan dihitung
     * @param mixed $condition Kondisi tambahan untuk query
     * @param int $days Jumlah hari untuk periode perbandingan (default 30 hari)
     * @return string
     */
    private function calculateTrend($model, $condition = null, $days = 30)
    {
        return Cache::remember("trend-{$model}-{$condition}", now()->addHours(6), function () use ($model, $condition, $days) {
            $currentPeriod = now()->subDays($days)->toDateString();
            $previousPeriod = now()->subDays($days * 2)->toDateString();

            $query = $model::query();

            // Handle special case for User model with role condition
            if ($model === User::class && $condition === 'user') {
                $query->where('role', 'user');
            }
            // Apply condition if provided for other models
            elseif ($condition) {
                $query->where('status', $condition);
            }

            $currentCount = $query->clone()
                ->where('created_at', '>=', $currentPeriod)
                ->count();

            $previousCount = $query->clone()
                ->whereBetween('created_at', [$previousPeriod, $currentPeriod])
                ->count();

            if ($previousCount == 0) {
                return $currentCount > 0 ? '+100%' : '0%';
            }

            $percentage = (($currentCount - $previousCount) / $previousCount) * 100;
            $formatted = number_format(abs($percentage), 1) . '%';

            return ($percentage >= 0 ? '+' : '-') . $formatted;
        });
    }
}
