<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('itemUnit.item');

        // Filter tanggal mulai dan sampai
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date);
        }

        // Filter jenis movement
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->orderBy('movement_date', 'desc')->paginate(15);

        // Jika request ajax, return json
        if ($request->ajax()) {
            return response()->json($movements);
        }

        return view('stock_movements.index', compact('movements'));
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
