<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'lunch_event_user_order_id',
        'item_name',
        'quantity',
        'price',
        'subtotal',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(LunchEventUserOrder::class, 'lunch_event_user_order_id');
    }
    
}