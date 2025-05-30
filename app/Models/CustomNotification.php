<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'borrow_request_id',
        'return_request_id',
        'type',
        'title',
        'body',
        'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class, 'borrow_request_id');
    }

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'return_request_id');
    }
}
