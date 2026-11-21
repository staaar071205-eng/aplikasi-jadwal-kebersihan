<?php

namespace Tests\Unit;

use App\Models\Jadwal;
use PHPUnit\Framework\TestCase;

class JadwalModelTest extends TestCase
{
    public function test_jam_ditampilkan_dalam_format_hh_mm(): void
    {
        $jadwal = new Jadwal(['jdw_jam_mulai' => '07:00:00', 'jdw_jam_selesai' => '09:30:00']);

        $this->assertSame('07:00', $jadwal->jamMulai());
        $this->assertSame('09:30', $jadwal->jamSelesai());
    }

    public function test_badge_sesuai_status(): void
    {
        $this->assertSame('bg-success', (new Jadwal(['jdw_status' => 'Selesai']))->badge());
        $this->assertSame('bg-secondary', (new Jadwal(['jdw_status' => 'Dibatalkan']))->badge());
        $this->assertSame('bg-primary', (new Jadwal(['jdw_status' => 'Terjadwal']))->badge());
    }
}
