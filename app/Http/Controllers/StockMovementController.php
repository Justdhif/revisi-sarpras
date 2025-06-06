<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['itemUnit.item'])
            ->latest('movement_date');

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->where('movement_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->where('movement_date', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('itemUnit.item', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortOptions = [
            'item_asc' => ['items.name', 'asc'],
            'item_desc' => ['items.name', 'desc']
        ];

        $sort = $request->get('sort', 'item_asc');
        $sortOption = $sortOptions[$sort] ?? $sortOptions['item_asc'];

        if (in_array($sort, ['item_asc', 'item_desc'])) {
            $query->join('item_units', 'stock_movements.item_unit_id', '=', 'item_units.id')
                ->join('items', 'item_units.item_id', '=', 'items.id')
                ->orderBy($sortOption[0], $sortOption[1]);
        } else {
            $query->orderBy($sortOption[0], $sortOption[1]);
        }

        // For AJAX requests, return JSON
        if ($request->ajax()) {
            $movements = $query->paginate(10);
            return response()->json([
                'data' => $movements->items(),
                'current_page' => $movements->currentPage(),
                'last_page' => $movements->lastPage(),
                'from' => $movements->firstItem(),
                'to' => $movements->lastItem(),
                'total' => $movements->total(),
                'links' => $movements->links()->elements,
            ]);
        }

        $movements = $query->paginate(10);
        $itemUnits = ItemUnit::with('item')->get();

        return view('stock_movements.index', compact('movements', 'itemUnits'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_unit_id' => 'required|exists:item_units,id',
            'type' => 'required|in:in,out,damaged',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        $movement = StockMovement::create($data);

        $itemUnit = $movement->itemUnit;

        if ($movement->type === 'in') {
            $itemUnit->quantity += $movement->quantity;
        } elseif (in_array($movement->type, ['out', 'damaged'])) {
            $itemUnit->quantity -= $movement->quantity;
        }

        $itemUnit->save();

        return redirect()->back()->with('success', 'Stock movement recorded');
    }
}
