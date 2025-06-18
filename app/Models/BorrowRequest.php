<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_date_expected',
        'return_date_expected',
        'status',
        'reason',
        'notes',
        'user_id',
        'handled_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function borrowDetail()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

