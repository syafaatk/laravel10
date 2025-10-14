<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCuti extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'days'];

    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'master_cuti_id');
    }
}