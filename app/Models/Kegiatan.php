<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'tb_kegiatan';

    protected $primaryKey = 'keg_id';

    protected $fillable = ['keg_nama', 'keg_keterangan'];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'jdw_keg_id', 'keg_id');
    }
}
