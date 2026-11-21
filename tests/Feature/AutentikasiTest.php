<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutentikasiTest extends TestCase
{
    use RefreshDatabase;

    private function buatUser(): User
    {
        return User::create([
            'user_nama' => 'Admin Uji',
            'user_email' => 'uji@mail.com',
            'user_password' => 'rahasia123',
        ]);
    }

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get('/')->assertOk()->assertSee('Login');
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_halaman_terlindungi(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/jadwal')->assertRedirect(route('login'));
    }

    public function test_login_berhasil_dengan_kredensial_benar(): void
    {
        $this->buatUser();

        $this->post('/login', ['user_email' => 'uji@mail.com', 'password' => 'rahasia123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->buatUser();

        $this->from('/')->post('/login', ['user_email' => 'uji@mail.com', 'password' => 'salah'])
            ->assertRedirect('/')
            ->assertSessionHasErrors('user_email');

        $this->assertGuest();
    }

    public function test_logout_mengakhiri_sesi(): void
    {
        $user = $this->buatUser();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
