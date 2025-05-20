<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('item', 'itemUnit')->where('user_id', Auth::id())->get();
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_unit_id' => 'required|exists:item_units,id',
        ]);

        // validasi jika quantity tidak sesuai
        $itemUnit = ItemUnit::findOrFail($request->item_unit_id);
        if ($itemUnit->quantity < $request->quantity || $itemUnit->status === 'borrowed') {
            return redirect()->back()->with('error', 'Stok tidak mencukupi / item sedang dipinjam');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'item_unit_id' => $request->item_unit_id,
            'quantity' => $request->quantity ?? 1,
        ]);

        $itemUnit->status = 'reserved';
        $itemUnit->save();

        return redirect()->back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $quantity = (int) $request->input('quantity');
        $item = $cart->item;

        // Validasi stok tersedia (hanya untuk item disposable)
        if ($item->is_disposable) {
            if ($quantity > $item->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok tersedia'
                ], 400);
            }
        }

        $cart->quantity = $quantity;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Jika ada item_unit_id, update status unit ke 'available'
        if ($cart->item_unit_id && $cart->itemUnit) {
            $cart->itemUnit->status = 'available';
            $cart->itemUnit->save();
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Dihapus dari keranjang');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'borrow_date_expected' => 'required|date',
            'return_date_expected' => 'required|date|after_or_equal:borrow_date_expected',
            'reason' => 'required|string',
        ]);

        $carts = Cart::where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        $borrow = BorrowRequest::create([
            'user_id' => Auth::id(),
            'borrow_date_expected' => $request->borrow_date_expected,
            'return_date_expected' => $request->return_date_expected,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);

        foreach ($carts as $cart) {
            BorrowDetail::create([
                'borrow_request_id' => $borrow->id,
                'item_unit_id' => $cart->item_unit_id,
                'quantity' => $cart->quantity,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Permintaan peminjaman dikirim.');
    }
}
