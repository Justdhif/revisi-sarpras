<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'description',
        'image_url',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }

    public function isConsumable()
    {
        return $this->type === 'consumable';
    }
}
