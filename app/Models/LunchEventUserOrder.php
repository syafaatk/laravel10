<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LunchEvent;
use App\Models\User;

class LunchEventUserOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'lunch_event_id',
        'user_id',
        'quantity',
        'total_price',
        'notes',
        'status',
    ];

    public function lunchEvent()
    {
        return $this->belongsTo(LunchEvent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(UserOrderDetail::class);
    }
}