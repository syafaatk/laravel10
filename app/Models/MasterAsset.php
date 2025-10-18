<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'status',
        'assigned_to',
        'assigned_date',
        'notes',
        'image',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'assigned_date' => 'date',
        'purchase_price' => 'integer',
    ];

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}