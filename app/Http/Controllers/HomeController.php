<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $totalCarts = Cart::where('user_id', Auth::id())->count();
        $items = ItemUnit::with('item')->get();
        return view('home', compact('items', 'totalCarts'));
    }
}
