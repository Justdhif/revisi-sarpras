<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'condition',
        'notes',
        'acquisition_source',
        'acquisition_date',
        'acquisition_notes',
        'status',
        'quantity',
        'qr_image_url',
        'item_id',
        'warehouse_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }
}
