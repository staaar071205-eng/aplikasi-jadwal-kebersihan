<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    public const STATUS = ['Terjadwal', 'Selesai', 'Dibatalkan'];

    protected $table = 'tb_jadwal';

    protected $primaryKey = 'jdw_id';

    protected $fillable = [
        'jdw_wil_id', 'jdw_keg_id', 'jdw_ptg_id', 'jdw_user_id',
        'jdw_tanggal', 'jdw_jam_mulai', 'jdw_jam_selesai',
        'jdw_status', 'jdw_catatan', 'jdw_foto',
    ];

    protected function casts(): array
    {
        return [
            'jdw_tanggal' => 'date',
        ];
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'jdw_wil_id', 'wil_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'jdw_keg_id', 'keg_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'jdw_ptg_id', 'ptg_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'jdw_user_id', 'user_id');
    }

    /** Jam dalam format HH:MM (untuk tampilan & input type=time). */
    public function jamMulai(): string
    {
        return substr((string) $this->jdw_jam_mulai, 0, 5);
    }

    public function jamSelesai(): string
    {
        return substr((string) $this->jdw_jam_selesai, 0, 5);
    }

    /** Kelas badge Bootstrap sesuai status. */
    public function badge(): string
    {
        return match ($this->jdw_status) {
            'Selesai' => 'bg-success',
            'Dibatalkan' => 'bg-secondary',
            default => 'bg-primary',
        };
    }
}
