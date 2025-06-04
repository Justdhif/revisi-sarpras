<?php
namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\User;
use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use App\Notifications\UserRequestedBorrowNotification;
use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\CustomNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    public function index()
    {
        $carts = Cart::with('item', 'itemUnit')->where('user_id', Auth::id())->get();
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_unit_id' => 'required|exists:item_units,id',
        ]);

        $itemUnit = ItemUnit::with('item')->findOrFail($request->item_unit_id);
        $itemType = $itemUnit->item->type; // ambil tipe item: 'consumable' atau 'non consumable'

        if ($itemType === 'consumable') {
            // Cek stok untuk consumable
            if ($itemUnit->quantity < $request->quantity) {
                return response()->json(['message' => 'Stok tidak mencukupi'], 400);
            }
        } else {
            // Cek status untuk non-consumable
            if ($itemUnit->status === 'borrowed' || $itemUnit->status === 'reserved') {
                return response()->json(['message' => 'Item sedang dipinjam atau sudah dipesan'], 400);
            }
        }

        $exists = Cart::where('user_id', Auth::id())
            ->where('item_id', $request->item_id)
            ->where('item_unit_id', $request->item_unit_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Item sudah ada di keranjang'], 409);
        }

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'item_unit_id' => $request->item_unit_id,
            'quantity' => $request->quantity ?? 1,
        ]);

        if ($itemType === 'non-consumable') {
            $itemUnit->status = 'reserved';
        }

        $itemUnit->save();

        return response()->json(['message' => 'Ditambahkan ke keranjang', 'data' => $cart]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $quantity = (int) $request->input('quantity');
        $item = $cart->item;

        if ($item->consumable && $quantity > $item->quantity) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }

        $cart->quantity = $quantity;
        $cart->save();

        if ($item->consumable) {
            $item->quantity -= $quantity;
            $item->save();
        }

        return response()->json(['message' => 'Kuantitas diubah']);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($cart->item_unit_id && $cart->itemUnit) {
            $cart->itemUnit->status = 'available';
            $cart->itemUnit->save();
        }

        $cart->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);
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
            return response()->json(['message' => 'Keranjang kosong'], 400);
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

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserRequestedBorrowNotification($borrow));
        }

        return response()->json(['message' => 'Permintaan peminjaman dikirim']);
    }
}
