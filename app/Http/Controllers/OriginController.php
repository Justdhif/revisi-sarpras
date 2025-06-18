<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    const PAGINATION_COUNT = 10;

    public function index()
    {
        $origins = Origin::withCount('users')
            ->latest()
            ->paginate(self::PAGINATION_COUNT);

        return view('origins.index', [
            'origins' => $origins,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateOriginRequest($request);
        Origin::create($validated);

        return redirect()->route('origins.index')
            ->with('success', 'Origin berhasil ditambahkan.');
    }

    public function update(Request $request, Origin $origin)
    {
        $validated = $this->validateOriginRequest($request);
        $origin->update($validated);

        return redirect()->route('origins.index')
            ->with('success', 'Origin berhasil diperbarui.');
    }

    public function destroy(Origin $origin)
    {
        $origin->delete();
        return redirect()->route('origins.index')
            ->with('success', 'Origin berhasil dihapus.');
    }

    private function validateOriginRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
        ]);
    }
}
