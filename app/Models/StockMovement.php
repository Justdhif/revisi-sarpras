<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'item_unit_id',
        'type',
        'quantity',
        'description',
        'movement_date',
    ];

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }
}
