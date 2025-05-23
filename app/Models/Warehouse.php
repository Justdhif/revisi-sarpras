<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
    ];

    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class);
    }

    public function hasCapacity($amount = 1)
    {
        return ($this->used_capacity + $amount) <= $this->capacity;
    }
}
