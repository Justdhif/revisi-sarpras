<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\ReturnDetail;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;

class ReturnDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($returnRequestId)
    {
        $returnRequest = ReturnRequest::findOrFail($returnRequestId);
        $itemUnits = ItemUnit::with('item')->get();

        return view('return_details.create', compact('returnRequest', 'itemUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'condition' => 'required|string',
            'item_unit_id' => 'required|exists:item_units,id',
            'return_request_id' => 'required|exists:return_requests,id',
        ]);

        $detail = ReturnDetail::create($validated);

        return response()->json([
            'message' => 'Return detail added.',
            'data' => $detail,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
