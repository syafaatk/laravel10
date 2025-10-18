<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LaunchEvent;

class MasterRestaurant extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'latitude',
        'longitude',
        'description',
        'image',
        'menu_1',
        'menu_2',
        'menu_3',
        'menu_4',
        'menu_5',
        'menu_6',
        'menu_7'
    ];

    public function restaurantEvent()
    {
        return $this->hasMany(LaunchEvent::class, 'restaurant_id', 'id');
    }
    
}