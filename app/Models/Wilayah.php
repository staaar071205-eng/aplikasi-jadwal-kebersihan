<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'tb_wilayah';

    protected $primaryKey = 'wil_id';

    protected $fillable = ['wil_nama', 'wil_keterangan'];

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'ptg_wil_id', 'wil_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'jdw_wil_id', 'wil_id');
    }
}
