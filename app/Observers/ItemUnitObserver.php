<?php

namespace App\Observers;

use App\Models\ItemUnit;

class ItemUnitObserver
{
    public function created(ItemUnit $unit)
    {
        logActivity('create item unit', 'Menambahkan unit SKU: ' . $unit->sku);
    }

    public function updated(ItemUnit $unit)
    {
        logActivity('update item unit', 'Mengubah unit SKU: ' . $unit->sku);
    }

    public function deleted(ItemUnit $unit)
    {
        logActivity('delete item unit', 'Menghapus unit SKU: ' . $unit->sku);
    }
}
