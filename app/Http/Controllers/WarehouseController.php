<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    const PAGINATION_COUNT = 10;

    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(self::PAGINATION_COUNT);
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateWarehouseRequest($request);
        Warehouse::create($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['itemUnits.item']);
        return view('warehouses.show', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $this->validateWarehouseRequest($request);
        $warehouse->update($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }

    private function validateWarehouseRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
    }
}
