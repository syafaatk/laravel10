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
        'tunjangan_jabatan',
        'tunjangan_golongan',
        'gaji_tunjangan_makan',
        'tunjangan_rumah',
        'gaji_tunjangan_transport',
        'tunjangan_tambahan',
        'tunjangan_extra',
        'gaji_bpjs',
        'premi_jkk_jkm',
        'potongan_pph21',
        'potongan_jmo',
        'is_active',
    ];

    protected $casts = [
        'tgl_mulai_kontrak' => 'datetime',
        'tgl_selesai_kontrak' => 'datetime',
        'gaji_pokok' => 'integer',
        'tunjangan_jabatan' => 'integer',
        'tunjangan_golongan' => 'integer',
        'gaji_tunjangan_makan' => 'integer',
        'tunjangan_rumah' => 'integer',
        'gaji_tunjangan_transport' => 'integer',
        'tunjangan_tambahan' => 'integer',
        'tunjangan_extra' => 'integer',
        'gaji_bpjs' => 'integer',
        'premi_jkk_jkm' => 'integer',
        'potongan_pph21' => 'integer',
        'potongan_jmo' => 'integer',
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
     * Get total gaji (all components).
     */
    public function getTotalGajiAttribute()
    {
        return $this->gaji_pokok +
               $this->tunjangan_jabatan +
               $this->tunjangan_golongan +
               $this->gaji_tunjangan_makan +
               $this->tunjangan_rumah +
               $this->gaji_tunjangan_transport +
               $this->tunjangan_tambahan +
               $this->tunjangan_extra +
               $this->gaji_bpjs +
               $this->premi_jkk_jkm;
    }

    /**
     * Get total allowances.
     */
    public function getTotalTunjanganAttribute()
    {
        return $this->tunjangan_jabatan +
               $this->tunjangan_golongan +
               $this->gaji_tunjangan_makan +
               $this->tunjangan_rumah +
               $this->gaji_tunjangan_transport +
               $this->tunjangan_tambahan +
               $this->tunjangan_extra;
    }

    /**
     * Get gross income.
     * Penghasilan Bruto = Gaji Pokok + Semua Tunjangan + Premi dibayar perusahaan (JKK, JKM)
     */
    public function getPenghasilanBrutoAttribute()
    {
        return $this->gaji_pokok + $this->total_tunjangan + $this->premi_jkk_jkm;
    }

    /**
     * Get total deductions.
     */
    public function getTotalPotonganAttribute()
    {
        // Assuming gaji_bpjs is company contribution, not employee deduction.
        // If it is, it should be added here.
        return $this->potongan_pph21 + $this->potongan_jmo;
    }

    /**
     * Get net income (take-home pay).
     * Penghasilan Netto = (Gaji Pokok + Tunjangan) - Potongan
     */
    public function getPenghasilanNettoAttribute()
    {
        // The premi_jkk_jkm is for tax calculation, not take-home pay.
        return ($this->gaji_pokok + $this->total_tunjangan) - $this->total_potongan;
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