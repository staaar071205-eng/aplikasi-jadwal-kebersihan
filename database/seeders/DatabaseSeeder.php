<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Petugas;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Data awal + data contoh (fiktif) untuk demonstrasi.
     * Akun login: admin@mail.com / admin
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['user_email' => 'admin@mail.com'],
            ['user_nama' => 'Administrator', 'user_password' => 'admin'],
        );

        // ---- Wilayah
        $wilayah = [];
        foreach ([
            ['Lorong I', 'Lorong I dan sekitarnya'],
            ['Lorong II', 'Lorong II dan sekitarnya'],
            ['Lorong III', 'Lorong III dan sekitarnya'],
            ['Area Masjid', 'Halaman dan lingkungan masjid'],
            ['Area Pasar', 'Pasar dan jalan utama lingkungan'],
        ] as [$nama, $ket]) {
            $wilayah[$nama] = Wilayah::firstOrCreate(['wil_nama' => $nama], ['wil_keterangan' => $ket]);
        }

        // ---- Jenis kegiatan
        $kegiatan = [];
        foreach ([
            ['Kerja Bakti', 'Gotong royong membersihkan lingkungan'],
            ['Pengangkutan Sampah', 'Pengumpulan dan pengangkutan sampah rumah tangga'],
            ['Pembersihan Parit', 'Membersihkan saluran air / drainase'],
            ['Pemangkasan Rumput', 'Memangkas rumput di fasilitas umum'],
        ] as [$nama, $ket]) {
            $kegiatan[$nama] = Kegiatan::firstOrCreate(['keg_nama' => $nama], ['keg_keterangan' => $ket]);
        }

        // ---- Petugas (nama fiktif)
        $petugas = [];
        foreach ([
            ['Lorong I', 'Budi Santoso', '081234560001', 'Aktif'],
            ['Lorong I', 'Siti Aminah', '081234560002', 'Aktif'],
            ['Lorong II', 'Ahmad Fauzi', '081234560003', 'Aktif'],
            ['Lorong II', 'Rina Marlina', '081234560004', 'Aktif'],
            ['Lorong III', 'Joko Susilo', '081234560005', 'Aktif'],
            ['Area Masjid', 'Hasan Basri', '081234560006', 'Aktif'],
            ['Area Pasar', 'Dewi Lestari', '081234560007', 'Aktif'],
            ['Area Pasar', 'Rudi Hartono', '081234560008', 'Nonaktif'],
        ] as [$wil, $nama, $telp, $status]) {
            $petugas[$nama] = Petugas::firstOrCreate(
                ['ptg_nama' => $nama, 'ptg_wil_id' => $wilayah[$wil]->wil_id],
                ['ptg_Tempat_&_Tanggal_Lahir' => $telp, 'ptg_status' => $status],
            );
        }

        // ---- Jadwal contoh (relatif terhadap hari ini agar dashboard terisi)
        if (Jadwal::count() === 0) {
            foreach ([
                [-6, 'Lorong I', 'Kerja Bakti', 'Budi Santoso', '07:00', '09:00', 'Selesai'],
                [-3, 'Area Pasar', 'Pengangkutan Sampah', 'Dewi Lestari', '06:00', '08:00', 'Selesai'],
                [-1, 'Lorong II', 'Pembersihan Parit', 'Ahmad Fauzi', '07:30', '10:00', 'Dibatalkan'],
                [0, 'Lorong III', 'Kerja Bakti', 'Joko Susilo', '07:00', '09:00', 'Terjadwal'],
                [0, 'Area Masjid', 'Pemangkasan Rumput', 'Hasan Basri', '15:00', '17:00', 'Terjadwal'],
                [2, 'Lorong I', 'Pengangkutan Sampah', 'Siti Aminah', '06:30', '08:00', 'Terjadwal'],
                [4, 'Lorong II', 'Pembersihan Parit', 'Rina Marlina', '07:00', '10:00', 'Terjadwal'],
                [6, 'Area Pasar', 'Kerja Bakti', 'Dewi Lestari', '06:00', '09:00', 'Terjadwal'],
            ] as [$selisih, $wil, $keg, $ptg, $mulai, $selesai, $status]) {
                Jadwal::create([
                    'jdw_wil_id' => $wilayah[$wil]->wil_id,
                    'jdw_keg_id' => $kegiatan[$keg]->keg_id,
                    'jdw_ptg_id' => $petugas[$ptg]->ptg_id,
                    'jdw_user_id' => $admin->user_id,
                    'jdw_tanggal' => today()->addDays($selisih),
                    'jdw_jam_mulai' => $mulai.':00',
                    'jdw_jam_selesai' => $selesai.':00',
                    'jdw_status' => $status,
                    'jdw_catatan' => $status === 'Dibatalkan' ? 'Dibatalkan karena hujan deras.' : null,
                ]);
            }
        }
    }
}
