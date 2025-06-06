<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::latest()->paginate(10);
        return view('origins.index', compact('origins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:siswa,guru',
            'name' => 'required|string|max:255',
        ]);

        Origin::create($request->only(['type', 'name']));
        return redirect()->route('origins.index')->with('success', 'Origin berhasil ditambahkan.');
    }

    public function update(Request $request, Origin $origin)
    {
        $request->validate([
            'type' => 'required|in:siswa,guru',
            'name' => 'required|string|max:255',
        ]);

        $origin->update($request->only(['type', 'name']));
        return redirect()->route('origins.index')->with('success', 'Origin berhasil diperbarui.');
    }

    public function destroy(Origin $origin)
    {
        $origin->delete();
        return redirect()->route('origins.index')->with('success', 'Origin berhasil dihapus.');
    }
}
