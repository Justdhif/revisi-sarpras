<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DamagedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_unit_id',
        'quantity',
        'damaged_at',
        'description',
    ];

    protected $casts = [
        'damaged_at' => 'date',
    ];

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }
}
