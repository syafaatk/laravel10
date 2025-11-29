<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKontrakUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kontrak',
        'tgl_mulai_kontrak',
        'tgl_selesai_kontrak',
        'gaji_pokok',
        'gaji_tunjangan_tetap',
        'gaji_tunjangan_makan',
        'gaji_tunjangan_transport',
        'gaji_tunjangan_lain',
        'gaji_bpjs',
        'is_active',
    ];

    protected $casts = [
        'tgl_mulai_kontrak' => 'datetime',
        'tgl_selesai_kontrak' => 'datetime',
        'gaji_pokok' => 'integer',
        'gaji_tunjangan_tetap' => 'integer',
        'gaji_tunjangan_makan' => 'integer',
        'gaji_tunjangan_transport' => 'integer',
        'gaji_tunjangan_lain' => 'integer',
        'gaji_bpjs' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total salary
     */
    public function getTotalGajiAttribute()
    {
        return $this->gaji_pokok + 
               $this->gaji_tunjangan_tetap + 
               $this->gaji_tunjangan_makan + 
               $this->gaji_tunjangan_transport + 
               $this->gaji_tunjangan_lain + 
               $this->gaji_bpjs;
    }

    /**
     * Scope untuk kontrak aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kontrak berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}