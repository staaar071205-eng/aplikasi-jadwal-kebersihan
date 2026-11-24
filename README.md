# Sistem Informasi Jadwal Kebersihan Tuntungan 2
UAS Pemrograman Berbasis Web Lanjutan                                        
Riski Amanda NIM : 0702243150, Sistem Informasi 1

**UAS Pemrograman Berbasis Web Lanjutan**  
UIN Sumatera Utara Medan, Fakultas Sains dan Teknologi                                                            
Dosen: Suendri, M.Kom · Semester Ganjil 2026/2027

Aplikasi web berbasis **Laravel 13.x**, **Bootstrap 5**, dan **MySQL/MariaDB** untuk mengelola jadwal kebersihan lingkungan
Tuntungan 2 (kerja bakti, pengangkutan sampah, pembersihan parit, dan sebagainya) yang selama ini dicatat secara manual.

## Studi Kasus

**Proses manual yang dikomputerisasi:** pembagian dan pencatatan jadwal kebersihan lingkungan.
Jadwal biasanya hanya ditulis di papan pengumuman atau grup percakapan sehingga mudah terlewat, sulit dilacak siapa
yang bertugas, kadang bertabrakan (satu petugas dijadwalkan dua kegiatan pada jam yang sama), dan tidak ada bukti
pelaksanaan. Sistem ini menggantikannya dengan data terpusat: wilayah, jenis kegiatan, petugas, jadwal, status
pelaksanaan, dan foto bukti.

## Fitur

| Fitur | Keterangan |
|---|---|
| Login & Logout | Halaman login, sesi aman (regenerasi sesi), logout via POST + CSRF, batas percobaan login |
| Dashboard | Ringkasan jumlah jadwal hari ini/bulan ini, wilayah, petugas aktif, status jadwal, jadwal hari ini dan 7 hari ke depan |
| Wilayah | Tambah, tampil (cari + pagination), ubah, hapus |
| Jenis Kegiatan | Tambah, tampil (cari + pagination), ubah, hapus |
| Petugas | Tambah, tampil (cari + filter wilayah/status), ubah, hapus |
| Jadwal Kebersihan | Tambah, tampil (cari + filter wilayah/status/rentang tanggal), detail, ubah, hapus, tandai selesai, upload foto bukti |
| Validasi | Semua input divalidasi; data unik; jam selesai harus setelah jam mulai; **petugas tidak boleh punya jadwal bertabrakan** |
| Integritas data | Data master yang masih dipakai tidak bisa dihapus (foreign key `RESTRICT` + pesan yang jelas) |

## Rancangan Database

Database `dbjadwalkebersihan` terdiri dari **5 tabel yang saling berhubungan**. Penamaan mengikuti materi praktikum
(`tb_` + kolom berawalan singkatan tabel).

```mermaid
erDiagram
    tb_users    ||--o{ tb_jadwal   : "mencatat (jdw_user_id)"
    tb_wilayah  ||--o{ tb_petugas  : "memiliki (ptg_wil_id)"
    tb_wilayah  ||--o{ tb_jadwal   : "dijadwalkan (jdw_wil_id)"
    tb_kegiatan ||--o{ tb_jadwal   : "jenis (jdw_keg_id)"
    tb_petugas  ||--o{ tb_jadwal   : "bertugas (jdw_ptg_id)"

    tb_users {
        bigint user_id PK
        varchar user_nama
        varchar user_email UK
        varchar user_password
    }
    tb_wilayah {
        bigint wil_id PK
        varchar wil_nama UK
        text wil_keterangan
    }
    tb_kegiatan {
        bigint keg_id PK
        varchar keg_nama UK
        text keg_keterangan
    }
    tb_petugas {
        bigint ptg_id PK
        bigint ptg_wil_id FK
        varchar ptg_nama
        varchar ptg_Tempat_&_Tanggal_Lahir
        text ptg_alamat
        varchar ptg_status
    }
    tb_jadwal {
        bigint jdw_id PK
        bigint jdw_wil_id FK
        bigint jdw_keg_id FK
        bigint jdw_ptg_id FK
        bigint jdw_user_id FK
        date jdw_tanggal
        time jdw_jam_mulai
        time jdw_jam_selesai
        varchar jdw_status
        text jdw_catatan
        varchar jdw_foto
    }
```

Seluruh tabel juga memiliki `created_at` dan `updated_at`. Semua foreign key memakai `ON UPDATE CASCADE ON DELETE RESTRICT`.

## Persyaratan

- **PHP 8.3 atau lebih baru** (syarat Laravel 13). Perhatian: XAMPP versi lama membawa PHP 8.2 — gunakan XAMPP dengan PHP 8.3+.
- Ekstensi PHP: `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `ctype`, `tokenizer`, `xml` (aktif secara bawaan di XAMPP).
- [Composer](https://getcomposer.org) 2.x
- MySQL 8 / MariaDB 10.4+ (bawaan XAMPP)

## Instalasi

Letakkan folder proyek, misalnya di `C:\xampp\htdocs\Praktik Matkul PBWL Sem 5\jadwal-kebersihan-tuntungan2`,
lalu jalankan di terminal dari dalam folder tersebut (nyalakan **MySQL** di XAMPP terlebih dahulu):

```bash
# 1. Pasang dependensi
composer install

# 2. Salin konfigurasi lingkungan
copy .env.example .env            # Windows  (Linux/Mac: cp .env.example .env)

# 3. Buat kunci aplikasi
php artisan key:generate

# 4. Buat database kosong bernama dbjadwalkebersihan (lewat phpMyAdmin), lalu:
php artisan migrate --seed

# 5. Jalankan server
php artisan serve
```
1. CMD
 cd /d "C:\xampp\htdocs\Praktik Matkul PBWL Sem 5\Tugas UAS pbwl"

php artisan serve

======= ctrl c ======

php artisan config:clear

php artisan serve

Buka **http://localhost:8000**.

Jika password MySQL Anda tidak kosong, ubah `DB_PASSWORD` di berkas `.env`.

**Alternatif tanpa artisan serve (Apache XAMPP):** akses
`http://localhost/Praktik%20Matkul%20PBWL%20Sem%205/jadwal-kebersihan-tuntungan2/public/`
(pastikan `mod_rewrite` aktif dan `AllowOverride All`).

### Akun login

| Email | Password |
|---|---|
| `admin@mail.com` | `admin` |

Data contoh (wilayah, petugas, jadwal) bersifat fiktif untuk demonstrasi.

## Pemetaan terhadap Soal UAS

| No | Ketentuan soal | Pemenuhan |
|---|---|---|
| 1 | Database MySQL/MariaDB, minimal 3 tabel berhubungan | 5 tabel berelasi dengan foreign key (`database/migrations`, `database/jadwal_kebersihan.sql`) |
| 2 | Menu sesuai kebutuhan sistem | Dashboard, Jadwal Kebersihan, Wilayah, Jenis Kegiatan, Petugas, Logout (`resources/views/layouts/app.blade.php`) |
| 3 | Halaman Login dan Logout | `AuthController`, `resources/views/auth/login.blade.php`; seluruh halaman lain dilindungi middleware `auth` |
| 4 | Input, tampil, edit, dan hapus data | CRUD penuh pada Wilayah, Jenis Kegiatan, Petugas, dan Jadwal |
| 5 | Source code di GitHub | Lihat bagian "Mengunggah ke GitHub" |
| — | Laravel 13.x + Bootstrap | `laravel/framework ^13.17`; Bootstrap 5.3 + Bootstrap Icons (lokal, `public/assets`) |

## Struktur Utama

```
app/
  Http/Controllers/   AuthController, DashboardController, WilayahController,
                      KegiatanController, PetugasController, JadwalController
  Models/             User, Wilayah, Kegiatan, Petugas, Jadwal
database/
  migrations/         5 tabel (tb_users, tb_wilayah, tb_kegiatan, tb_petugas, tb_jadwal)
  seeders/            DatabaseSeeder (akun admin + data contoh)
  jadwal_kebersihan.sql   Skrip SQL setara migrate + seed
lang/id/validation.php    Pesan validasi Bahasa Indonesia
public/assets/            Bootstrap, Bootstrap Icons, CSS & JS aplikasi
public/uploads/jadwal/    Foto bukti kegiatan (di luar git)
resources/views/          layouts, auth, dashboard, wilayah, kegiatan, petugas, jadwal
routes/web.php            Seluruh route aplikasi
tests/Feature/            Pengujian otomatis (autentikasi & jadwal)
```

## Rute Aplikasi

| Method | URL | Keterangan |
|---|---|---|
| GET | `/` | Halaman login |
| POST | `/login` | Proses login |
| POST | `/logout` | Logout |
| GET | `/dashboard` | Dashboard |
| GET/POST | `/wilayah`, `/kegiatan`, `/petugas`, `/jadwal` | Daftar & simpan |
| GET | `/{modul}/create` | Form input |
| GET/PUT/DELETE | `/{modul}/{id}` , `/{modul}/{id}/edit` | Ubah & hapus (`/jadwal/{id}` juga menampilkan detail) |
| PATCH | `/jadwal/{id}/selesai` | Tandai jadwal selesai |

Daftar lengkap: `php artisan route:list`.

## Keamanan

- Password disimpan dengan hash **bcrypt**; sesi diregenerasi saat login; proteksi **CSRF** pada semua form.
- Query memakai Eloquent / parameter binding (aman dari SQL injection); keluaran Blade di-escape otomatis (aman dari XSS).
- Upload foto divalidasi (tipe gambar, maksimal 2 MB) dan disimpan dengan nama acak.
- Halaman selain login hanya dapat diakses setelah login.

## Pengujian

```bash
php artisan test
```

Pengujian memakai SQLite in-memory (lihat `phpunit.xml`) sehingga tidak menyentuh database MySQL Anda.
Pastikan ekstensi `pdo_sqlite` aktif di PHP Anda.


## Lisensi

Dibuat untuk keperluan akademik. Kerangka Laravel berlisensi [MIT](https://opensource.org/licenses/MIT).
