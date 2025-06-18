<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'notes',
        'borrow_request_id',
        'handled_by',
    ];

    public function handler() {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
