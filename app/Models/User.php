<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'no_wa',
        'motor',
        'ukuran_baju',
        'tgl_masuk',
        'attachment_ttd',
        'nopeg',
        'kontrak',
        'jabatan',
        'norek',
        'bank',
        'gaji_tunjangan_tetap',
        'gaji_tunjangan_makan',
        'gaji_tunjangan_transport',
        'gaji_pokok',
        'gaji_bpjs',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'address' => 'string',
        'no_wa' => 'string',
        'motor' => 'string',
        'ukuran_baju' => 'string',
        'tgl_masuk' => 'datetime',
        'attachment_ttd' => 'string',
    ];

    public function cutiApproved()
    {
        return $this->hasMany(Cuti::class)->where('status', 'approved')->where('master_cuti_id', 1);
    }

    public function reimbursements()
    {
        return $this->hasMany(Reimbursement::class);
    }

    public function cutis()
    {
        return $this->hasMany(Cuti::class);
    }

    public function laporanReimbursements()
    {
        return $this->hasMany(LaporanReimbursement::class);
    }

    public function penilaianPegawai()
    {
        return $this->hasMany(PenilaianPegawai::class, 'user_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(PenilaianPegawai::class, 'reviewer_id');
    }
    
    public function assetAssign()
    {
        return $this->hasMany(AssetAssign::class);
    }
}
