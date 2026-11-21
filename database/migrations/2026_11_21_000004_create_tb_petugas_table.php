<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel petugas / warga penanggung jawab kebersihan.
     * Relasi: setiap petugas berasal dari satu wilayah.
     */
    public function up(): void
    {
        Schema::create('tb_petugas', function (Blueprint $table) {
            $table->id('ptg_id');
            $table->foreignId('ptg_wil_id')
                ->constrained('tb_wilayah', 'wil_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('ptg_nama', 100);
            $table->string('ptg_Tempat_&_Tanggal_Lahir', 20)->nullable();
            $table->text('ptg_alamat')->nullable();
            $table->string('ptg_status', 10)->default('Aktif'); // Aktif | Nonaktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_petugas');
    }
};
