<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;


class LaunchEvent extends Model
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

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    
}