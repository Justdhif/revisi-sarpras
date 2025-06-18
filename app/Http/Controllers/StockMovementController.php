<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\RepairedItem;
use App\Models\StockMovement;
use App\Models\DamagedItem;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    const PAGINATION_COUNT = 10;
    const MOVEMENT_TYPES = ['in', 'out', 'damaged', 'repaired'];
    const DEFAULT_SORT = 'item_asc';

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'movement_date');
        $sortDirection = $request->get('direction', 'desc');

        $query = StockMovement::with(['itemUnit.item'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('itemUnit.item', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->movement_type, function ($q) use ($request) {
                $q->where('movement_type', $request->movement_type);
            })
            ->when($request->start_date, function ($q) use ($request) {
                $q->where('movement_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                $q->where('movement_date', '<=', $request->end_date);
            });

        // Handle sorting
        if ($sortField === 'item_name') {
            $query->join('item_units', 'stock_movements.item_unit_id', '=', 'item_units.id')
                ->join('items', 'item_units.item_id', '=', 'items.id')
                ->orderBy('items.name', $sortDirection)
                ->select('stock_movements.*');
        } elseif ($sortField === 'movement_type') {
            $query->orderBy('movement_type', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $movements = $query->paginate(10);
        $itemUnits = ItemUnit::latest()->get();

        return view('stock_movements.index', [
            'movements' => $movements,
            'itemUnits' => $itemUnits,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_unit_id' => 'required|exists:item_units,id',
            'movement_type' => 'required|in:' . implode(',', self::MOVEMENT_TYPES),
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        $movement = StockMovement::create($validated);
        $itemUnit = $movement->itemUnit;

        switch ($movement->type) {
            case 'in':
                $itemUnit->quantity += $movement->quantity;
                break;
            case 'out':
            case 'damaged':
                $itemUnit->quantity -= $movement->quantity;
                break;
        }

        $itemUnit->save();

        if ($movement->movement_type === 'damaged') {
            DamagedItem::create([
                'item_unit_id' => $movement->item_unit_id,
                'quantity' => $movement->quantity,
                'damaged_at' => $movement->movement_date,
                'description' => $movement->description,
            ]);

            $itemUnit->condition = 'damaged';
            $itemUnit->status = 'unvailable';
            $itemUnit->save();
        }

        return redirect()->back()
            ->with('success', 'Stock movement recorded');
    }

    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();
        return redirect()->back()
            ->with('success', 'Stock movement deleted');
    }
}
