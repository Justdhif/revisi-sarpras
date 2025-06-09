<?php

namespace App\Http\Controllers;

use App\Models\DamagedItem;
use Illuminate\Http\Request;

class DamagedItemController extends Controller
{
    const PAGINATION_COUNT = 10;

    public function index()
    {
        $damagedItems = DamagedItem::with(['itemUnit.item'])
            ->latest()
            ->paginate(self::PAGINATION_COUNT);

        return view('damaged_items.index', compact('damagedItems'));
    }
}
