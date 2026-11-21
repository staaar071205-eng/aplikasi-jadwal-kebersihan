<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Petugas;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'user_nama' => 'Admin Uji',
            'user_email' => 'uji@mail.com',
            'user_password' => 'rahasia123',
        ]);
    }

    private function dataMaster(): array
    {
        $wilayah = Wilayah::create(['wil_nama' => 'Lorong Uji']);
        $kegiatan = Kegiatan::create(['keg_nama' => 'Kerja Bakti']);
        $petugas = Petugas::create(['ptg_wil_id' => $wilayah->wil_id, 'ptg_nama' => 'Petugas Uji', 'ptg_status' => 'Aktif']);

        return [$wilayah, $kegiatan, $petugas];
    }

    private function payload(array $master, array $override = []): array
    {
        [$wilayah, $kegiatan, $petugas] = $master;

        return array_merge([
            'jdw_wil_id' => $wilayah->wil_id,
            'jdw_keg_id' => $kegiatan->keg_id,
            'jdw_ptg_id' => $petugas->ptg_id,
            'jdw_tanggal' => '2026-12-01',
            'jdw_jam_mulai' => '07:00',
            'jdw_jam_selesai' => '09:00',
            'jdw_status' => 'Terjadwal',
        ], $override);
    }

    public function test_halaman_utama_dapat_dibuka_setelah_login(): void
    {
        $this->actingAs($this->user);

        foreach (['dashboard', 'wilayah.index', 'kegiatan.index', 'petugas.index', 'jadwal.index'] as $nama) {
            $this->get(route($nama))->assertOk();
        }
    }

    public function test_menambah_wilayah(): void
    {
        $this->actingAs($this->user)
            ->post(route('wilayah.store'), ['wil_nama' => 'Lorong Baru'])
            ->assertRedirect(route('wilayah.index'));

        $this->assertDatabaseHas('tb_wilayah', ['wil_nama' => 'Lorong Baru']);
    }

    public function test_nama_wilayah_harus_unik(): void
    {
        Wilayah::create(['wil_nama' => 'Lorong Sama']);

        $this->actingAs($this->user)
            ->post(route('wilayah.store'), ['wil_nama' => 'Lorong Sama'])
            ->assertSessionHasErrors('wil_nama');
    }

    public function test_wilayah_yang_dipakai_tidak_bisa_dihapus(): void
    {
        [$wilayah] = $this->dataMaster();

        $this->actingAs($this->user)
            ->delete(route('wilayah.destroy', $wilayah))
            ->assertSessionHas('gagal');

        $this->assertDatabaseHas('tb_wilayah', ['wil_id' => $wilayah->wil_id]);
    }

    public function test_menambah_mengubah_dan_menghapus_jadwal(): void
    {
        $master = $this->dataMaster();
        $this->actingAs($this->user);

        $this->post(route('jadwal.store'), $this->payload($master))
            ->assertRedirect(route('jadwal.index'));

        $jadwal = Jadwal::firstOrFail();
        $this->assertSame($this->user->user_id, $jadwal->jdw_user_id);

        $this->put(route('jadwal.update', $jadwal), $this->payload($master, ['jdw_status' => 'Selesai']))
            ->assertRedirect(route('jadwal.index'));
        $this->assertSame('Selesai', $jadwal->fresh()->jdw_status);

        $this->delete(route('jadwal.destroy', $jadwal))->assertRedirect(route('jadwal.index'));
        $this->assertDatabaseCount('tb_jadwal', 0);
    }

    public function test_jam_selesai_harus_setelah_jam_mulai(): void
    {
        $master = $this->dataMaster();

        $this->actingAs($this->user)
            ->post(route('jadwal.store'), $this->payload($master, ['jdw_jam_mulai' => '10:00', 'jdw_jam_selesai' => '09:00']))
            ->assertSessionHasErrors('jdw_jam_selesai');
    }

    public function test_petugas_tidak_boleh_punya_jadwal_bertabrakan(): void
    {
        $master = $this->dataMaster();
        $this->actingAs($this->user);

        $this->post(route('jadwal.store'), $this->payload($master))->assertSessionHasNoErrors();

        $this->post(route('jadwal.store'), $this->payload($master, ['jdw_jam_mulai' => '08:00', 'jdw_jam_selesai' => '10:00']))
            ->assertSessionHasErrors('jdw_ptg_id');

        $this->assertDatabaseCount('tb_jadwal', 1);
    }

    public function test_tandai_selesai(): void
    {
        $master = $this->dataMaster();
        $this->actingAs($this->user)->post(route('jadwal.store'), $this->payload($master));

        $jadwal = Jadwal::firstOrFail();
        $this->patch(route('jadwal.selesai', $jadwal))->assertRedirect();

        $this->assertSame('Selesai', $jadwal->fresh()->jdw_status);
    }
}
