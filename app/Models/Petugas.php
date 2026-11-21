<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    public const STATUS = ['Aktif', 'Nonaktif'];

    protected $table = 'tb_petugas';

    protected $primaryKey = 'ptg_id';

    protected $fillable = [
        'ptg_wil_id',
        'ptg_nama',
        'ptg_Tempat_&_Tanggal_Lahir',
        'ptg_alamat',
        'ptg_status',
    ];

    public function wilayah()
    {
        return $this->belongsTo(
            Wilayah::class,
            'ptg_wil_id',
            'wil_id'
        );
    }

    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'jdw_ptg_id',
            'ptg_id'
        );
    }
}
