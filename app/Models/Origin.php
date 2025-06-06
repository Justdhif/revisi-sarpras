<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Origin extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
