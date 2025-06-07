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
    const TREND_DAYS = 30;
    const RECENT_ITEMS_COUNT = 5;
    const CACHE_HOURS = 6;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->with('error', 'Email atau password salah!');
        }

        if ($user->role === 'super-admin' && Hash::check($credentials['password'], $user->password)) {
            $this->handleSuccessfulLogin($user, $remember);
            return redirect()->route('dashboard')
                ->with('success', 'Hai ' . $user->username . ', selamat datang!');
        }

        if ($user->role === 'user') {
            return redirect()->back()->with('error', 'Akun mu bukan sebagai admin');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout()
    {
        if ($user = Auth::user()) {
            $user->update(['active' => false]);
        }

        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    public function dashboard()
    {
        $stats = $this->getDashboardStatistics();
        $recentData = $this->getRecentData();

        return view('dashboard', array_merge($stats, $recentData));
    }

    private function handleSuccessfulLogin(User $user, bool $remember)
    {
        Auth::login($user, $remember);
        $user->update([
            'last_logined_at' => now(),
            'active' => true,
        ]);
    }

    private function getDashboardStatistics()
    {
        return [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalUsersTrend' => $this->calculateTrend(User::class, 'user'),

            'totalItems' => Item::count(),
            'totalItemsTrend' => $this->calculateTrend(Item::class),

            'totalBorrows' => BorrowRequest::count(),
            'totalBorrowsTrend' => $this->calculateTrend(BorrowRequest::class),

            'totalReturns' => ReturnRequest::count(),
            'totalReturnsTrend' => $this->calculateTrend(ReturnRequest::class),
        ];
    }

    private function getRecentData()
    {
        return [
            'recentLogs' => ActivityLog::latest()->take(self::RECENT_ITEMS_COUNT)->get(),
            'recentBorrows' => BorrowRequest::with('user')->latest()->take(self::RECENT_ITEMS_COUNT)->get(),
            'recentReturns' => ReturnRequest::with('borrowRequest.user')->latest()->take(self::RECENT_ITEMS_COUNT)->get(),
            'recentItems' => Item::with('category')->latest()->take(self::RECENT_ITEMS_COUNT)->get(),
            'recentItemUnits' => ItemUnit::with('item.category')->latest()->take(self::RECENT_ITEMS_COUNT)->get(),
            'sku' => ItemUnit::pluck('sku'),
        ];
    }

    private function calculateTrend(string $model, ?string $condition = null, int $days = self::TREND_DAYS)
    {
        return Cache::remember(
            "trend-{$model}-{$condition}",
            now()->addHours(self::CACHE_HOURS),
            function () use ($model, $condition, $days) {
                $currentPeriod = now()->subDays($days);
                $previousPeriod = now()->subDays($days * 2);

                $query = $model::query();

                if ($model === User::class && $condition === 'user') {
                    $query->where('role', 'user');
                } elseif ($condition) {
                    $query->where('status', $condition);
                }

                $currentCount = $query->clone()
                    ->where('created_at', '>=', $currentPeriod)
                    ->count();

                $previousCount = $query->clone()
                    ->whereBetween('created_at', [$previousPeriod, $currentPeriod])
                    ->count();

                return $this->calculatePercentageChange($currentCount, $previousCount);
            }
        );
    }

    private function calculatePercentageChange(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $percentage = (($current - $previous) / $previous) * 100;
        $formatted = number_format(abs($percentage), 1) . '%';

        return ($percentage >= 0 ? '+' : '-') . $formatted;
    }
}
