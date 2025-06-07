<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = $this->getUserCart();
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_unit_id' => 'required|exists:item_units,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $itemUnit = ItemUnit::findOrFail($validated['item_unit_id']);

        if (!$this->validateItemAvailability($itemUnit, $validated['quantity'] ?? 1)) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi / item sedang dipinjam');
        }

        if ($this->itemAlreadyInCart($validated['item_id'], $validated['item_unit_id'])) {
            return redirect()->back()
                ->with('error', 'Item sudah ada di keranjang');
        }

        $this->addToCart($validated, $itemUnit);

        return redirect()->back()
            ->with('success', 'Ditambahkan ke keranjang');
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = $this->findUserCartItem($id);
        $quantity = (int) $request->input('quantity');

        if (!$this->validateStockAvailability($cart->item, $quantity)) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi');
        }

        $cart->update(['quantity' => $quantity]);

        return redirect()->back()
            ->with('success', 'Kuantitas diubah');
    }

    public function destroy($id)
    {
        $cart = $this->findUserCartItem($id);
        $this->removeFromCart($cart);

        return redirect()->back()
            ->with('success', 'Dihapus dari keranjang');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'borrow_date_expected' => 'required|date',
            'return_date_expected' => 'required|date|after_or_equal:borrow_date_expected',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $carts = $this->getUserCart();

        if ($carts->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Keranjang kosong.');
        }

        $borrowRequest = $this->createBorrowRequest($validated);
        $this->createBorrowDetails($borrowRequest, $carts);
        $this->clearUserCart();

        return redirect()->back()
            ->with('success', 'Permintaan peminjaman dikirim.');
    }

    private function getUserCart()
    {
        return Cart::with('item', 'itemUnit')
            ->where('user_id', Auth::id())
            ->get();
    }

    private function validateItemAvailability(ItemUnit $itemUnit, int $quantity): bool
    {
        return $itemUnit->quantity >= $quantity &&
            $itemUnit->status !== 'borrowed';
    }

    private function itemAlreadyInCart(int $itemId, int $itemUnitId): bool
    {
        return Cart::where('user_id', Auth::id())
            ->where('item_id', $itemId)
            ->where('item_unit_id', $itemUnitId)
            ->exists();
    }

    private function addToCart(array $data, ItemUnit $itemUnit): void
    {
        Cart::create([
            'user_id' => Auth::id(),
            'item_id' => $data['item_id'],
            'item_unit_id' => $data['item_unit_id'],
            'quantity' => $data['quantity'] ?? 1,
        ]);

        $itemUnit->update(['status' => 'reserved']);
    }

    private function findUserCartItem($id)
    {
        return Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    private function validateStockAvailability(Item $item, int $quantity): bool
    {
        return !$item->consumable || $quantity <= $item->quantity;
    }

    private function removeFromCart(Cart $cart): void
    {
        if ($cart->itemUnit) {
            $cart->itemUnit->update(['status' => 'available']);
        }
        $cart->delete();
    }

    private function createBorrowRequest(array $data): BorrowRequest
    {
        return BorrowRequest::create([
            'user_id' => Auth::id(),
            'borrow_date_expected' => $data['borrow_date_expected'],
            'return_date_expected' => $data['return_date_expected'],
            'reason' => $data['reason'],
            'notes' => $data['notes'],
        ]);
    }

    private function createBorrowDetails(BorrowRequest $borrowRequest, $carts): void
    {
        foreach ($carts as $cart) {
            BorrowDetail::create([
                'borrow_request_id' => $borrowRequest->id,
                'item_unit_id' => $cart->item_unit_id,
                'quantity' => $cart->quantity,
            ]);
        }
    }

    private function clearUserCart(): void
    {
        Cart::where('user_id', Auth::id())->delete();
    }
}
