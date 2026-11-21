<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel jadwal kebersihan (tabel transaksi utama).
     * Relasi: wilayah, jenis kegiatan, petugas penanggung jawab, dan user pencatat.
     */
    public function up(): void
    {
        Schema::create('tb_jadwal', function (Blueprint $table) {
            $table->id('jdw_id');
            $table->foreignId('jdw_wil_id')
                ->constrained('tb_wilayah', 'wil_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('jdw_keg_id')
                ->constrained('tb_kegiatan', 'keg_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('jdw_ptg_id')
                ->constrained('tb_petugas', 'ptg_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('jdw_user_id')
                ->constrained('tb_users', 'user_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('jdw_tanggal')->index();
            $table->time('jdw_jam_mulai');
            $table->time('jdw_jam_selesai');
            $table->string('jdw_status', 15)->default('Terjadwal'); // Terjadwal | Selesai | Dibatalkan
            $table->text('jdw_catatan')->nullable();
            $table->string('jdw_foto', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_jadwal');
    }
};
