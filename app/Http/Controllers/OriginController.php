<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::latest()->paginate(10);
        // Jumlah user dri origin tersebut
        $userOriginCount = Origin::with('users')->count();
        return view('origins.index', compact('origins', 'userOriginCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Origin::create($request->only(['name']));
        return redirect()->route('origins.index')->with('success', 'Origin berhasil ditambahkan.');
    }

    public function update(Request $request, Origin $origin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $origin->update($request->only(['name']));
        return redirect()->route('origins.index')->with('success', 'Origin berhasil diperbarui.');
    }

    public function destroy(Origin $origin)
    {
        $origin->delete();
        return redirect()->route('origins.index')->with('success', 'Origin berhasil dihapus.');
    }
}
