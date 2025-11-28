<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterRestaurant;


class LunchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_date',
        'restaurant_id',
        'status',
        'image',
        'description',
        'nota',
    ];

    protected $cast = [
        'event_date' => 'date',
    ];

    public function restaurant()
    {
        return $this->belongsTo(MasterRestaurant::class);
    }

    public function orders()
    {
        return $this->hasMany(LunchEventUserOrder::class);
    }
    
}