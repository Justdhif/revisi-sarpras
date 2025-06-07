<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\BorrowDetail;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class BorrowDetailController extends Controller
{
    public function index()
    {
        $borrowDetails = BorrowDetail::with([
            'borrowRequest.user',
            'borrowRequest.approver',
            'itemUnit.item'
        ])->latest()->get();

        return view('borrow_details.index', compact('borrowDetails'));
    }

    public function create($borrowRequestId)
    {
        $borrowRequest = BorrowRequest::with('user')->findOrFail($borrowRequestId);
        $itemUnits = $this->getAvailableItemUnits();

        return view('borrow_details.create', compact('borrowRequest', 'itemUnits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrow_request_id' => 'required|exists:borrow_requests,id',
            'item_unit_id' => 'required|exists:item_units,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $unit = ItemUnit::findOrFail($validated['item_unit_id']);

        if (!$this->validateItemAvailability($unit, $validated['quantity'])) {
            return back()->with('error', $this->getAvailabilityErrorMessage($unit));
        }

        $this->updateItemStatus($unit, $validated['quantity']);
        BorrowDetail::create($validated);

        return redirect()
            ->route('borrow-requests.show', $validated['borrow_request_id'])
            ->with('success', 'Barang berhasil ditambahkan ke peminjaman.');
    }

    public function show(string $id)
    {
        // Implementation pending
    }

    public function edit(string $id)
    {
        // Implementation pending
    }

    public function update(Request $request, string $id)
    {
        // Implementation pending
    }

    public function destroy(string $id)
    {
        // Implementation pending
    }

    private function getAvailableItemUnits()
    {
        return ItemUnit::with('item')
            ->where('status', 'available')
            ->whereHas('item', function ($query) {
                $query->where(function ($q) {
                    $q->where('type', '!=', 'consumable')
                        ->orWhere(function ($q2) {
                            $q2->where('type', 'consumable')
                                ->where('quantity', '>', 0);
                        });
                });
            })
            ->get();
    }

    private function validateItemAvailability(ItemUnit $unit, int $quantity): bool
    {
        if ($unit->item->type === 'consumable') {
            return $unit->quantity !== null && $unit->quantity >= $quantity;
        }

        return $unit->status === 'available';
    }

    private function getAvailabilityErrorMessage(ItemUnit $unit): string
    {
        return $unit->item->type === 'consumable'
            ? 'Stok tidak mencukupi untuk item: ' . $unit->item->name
            : 'Item tidak tersedia untuk dipinjam.';
    }

    private function updateItemStatus(ItemUnit $unit, int $quantity): void
    {
        if ($unit->item->type === 'consumable') {
            $unit->quantity -= $quantity;
            $unit->status = $unit->quantity === 0 ? 'unavailable' : 'available';
        } else {
            $unit->status = 'borrowed';
        }

        $unit->save();
    }
}
