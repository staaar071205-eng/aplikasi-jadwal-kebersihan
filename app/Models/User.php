<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Akun login. Memakai tabel tb_users dengan kolom berawalan user_
 * (selaras dengan materi praktikum: user_email, user_password, user_nama).
 */
class User extends Authenticatable
{
    protected $table = 'tb_users';

    protected $primaryKey = 'user_id';

    protected $fillable = ['user_nama', 'user_email', 'user_password'];

    protected $hidden = ['user_password'];

    protected function casts(): array
    {
        return [
            'user_password' => 'hashed',
        ];
    }

    // Kolom password bukan "password" (bawaan Laravel)
    public function getAuthPassword(): string
    {
        return $this->user_password;
    }

    public function getAuthPasswordName(): string
    {
        return 'user_password';
    }

    // Fitur "ingat saya" tidak dipakai, sehingga tidak butuh kolom remember_token
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'jdw_user_id', 'user_id');
    }
}
