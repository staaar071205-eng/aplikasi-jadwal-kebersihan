CREATE DATABASE IF NOT EXISTS dbjadwalkebersihan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbjadwalkebersihan;

CREATE TABLE tb_users (
	user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_nama VARCHAR(100) NOT NULL,
	user_email VARCHAR(100) NOT NULL,
	user_password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP NULL DEFAULT NULL,
	updated_at TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (user_id),
	UNIQUE KEY tb_users_user_email_unique (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_wilayah (
	wil_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	wil_nama VARCHAR(100) NOT NULL,
	wil_keterangan TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT NULL,
	updated_at TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (wil_id),
	UNIQUE KEY tb_wilayah_wil_nama_unique (wil_nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_kegiatan (
	keg_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	keg_nama VARCHAR(100) NOT NULL,
	keg_keterangan TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT NULL,
	updated_at TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (keg_id),
	UNIQUE KEY tb_kegiatan_keg_nama_unique (keg_nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_petugas (
    ptg_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    ptg_wil_id BIGINT UNSIGNED NOT NULL,
    ptg_nama VARCHAR(100) NOT NULL,
    `ptg_Tempat_&_Tanggal_Lahir` VARCHAR(100) NULL,
    ptg_alamat TEXT NULL,
    ptg_status VARCHAR(10) NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (ptg_id),
    KEY tb_petugas_ptg_wil_id_foreign (ptg_wil_id),
    CONSTRAINT tb_petugas_ptg_wil_id_foreign
    FOREIGN KEY (ptg_wil_id) REFERENCES tb_wilayah (wil_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_jadwal (
	jdw_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	jdw_wil_id BIGINT UNSIGNED NOT NULL,
	jdw_keg_id BIGINT UNSIGNED NOT NULL,
	jdw_ptg_id BIGINT UNSIGNED NOT NULL,
	jdw_user_id BIGINT UNSIGNED NOT NULL,
	jdw_tanggal DATE NOT NULL,
	jdw_jam_mulai TIME NOT NULL,
	jdw_jam_selesai TIME NOT NULL,
	jdw_status VARCHAR(15) NOT NULL DEFAULT 'Terjadwal',
	jdw_catatan TEXT NULL,
	jdw_foto VARCHAR(100) NULL,
	created_at TIMESTAMP NULL DEFAULT NULL,
	updated_at TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (jdw_id),
	KEY tb_jadwal_jdw_tanggal_index (jdw_tanggal),
	KEY tb_jadwal_jdw_wil_id_foreign (jdw_wil_id),
	KEY tb_jadwal_jdw_keg_id_foreign (jdw_keg_id),
	KEY tb_jadwal_jdw_ptg_id_foreign (jdw_ptg_id),
	KEY tb_jadwal_jdw_user_id_foreign (jdw_user_id),
	CONSTRAINT tb_jadwal_jdw_wil_id_foreign FOREIGN KEY (jdw_wil_id) REFERENCES tb_wilayah (wil_id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT tb_jadwal_jdw_keg_id_foreign FOREIGN KEY (jdw_keg_id) REFERENCES tb_kegiatan (keg_id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT tb_jadwal_jdw_ptg_id_foreign FOREIGN KEY (jdw_ptg_id) REFERENCES tb_petugas (ptg_id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT tb_jadwal_jdw_user_id_foreign FOREIGN KEY (jdw_user_id) REFERENCES tb_users (user_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tb_users (user_id, user_nama, user_email, user_password, created_at, updated_at) VALUES
(1, 'Administrator', 'admin@mail.com', '$2y$10$a8siOxDBxsX7IPSqC6ApXOGjWwExLebPVbYSaHLYkM7P0nXlRZokK', NOW(), NOW());

INSERT INTO tb_wilayah (wil_id, wil_nama, wil_keterangan, created_at, updated_at) VALUES
(1, 'Lorong I', 'Lorong I dan sekitarnya', NOW(), NOW()),
(2, 'Lorong II', 'Lorong II dan sekitarnya', NOW(), NOW()),
(3, 'Lorong III', 'Lorong III dan sekitarnya', NOW(), NOW()),
(4, 'Area Masjid', 'Halaman dan lingkungan masjid', NOW(), NOW()),
(5, 'Area Pasar', 'Pasar dan jalan utama lingkungan', NOW(), NOW());

INSERT INTO tb_kegiatan (keg_id, keg_nama, keg_keterangan, created_at, updated_at) VALUES
(1, 'Kerja Bakti', 'Gotong royong membersihkan lingkungan', NOW(), NOW()),
(2, 'Pengangkutan Sampah', 'Pengumpulan dan pengangkutan sampah rumah tangga', NOW(), NOW()),
(3, 'Pembersihan Parit', 'Membersihkan saluran air / drainase', NOW(), NOW()),
(4, 'Pemangkasan Rumput', 'Memangkas rumput di fasilitas umum', NOW(), NOW());

INSERT INTO tb_petugas (ptg_id, ptg_wil_id, ptg_nama, `ptg_Tempat_&_Tanggal_Lahir`, ptg_alamat, ptg_status, created_at, updated_at) VALUES
(1, 1, 'Budi Santoso', 'Bandung, 15 Maret 1990', NULL, 'Aktif', NOW(), NOW()),
(2, 1, 'Siti Aminah', 'Jakarta, 20 April 1985', NULL, 'Aktif', NOW(), NOW()),
(3, 2, 'Ahmad Fauzi', 'Surabaya, 10 Mei 1992', NULL, 'Aktif', NOW(), NOW()),
(4, 2, 'Rina Marlina', 'Medan, 5 Juni 1988', NULL, 'Aktif', NOW(), NOW()),
(5, 3, 'Joko Susilo', 'Yogyakarta, 12 Juli 1990', NULL, 'Aktif', NOW(), NOW()),
(6, 4, 'Hasan Basri', 'Bandung, 15 Maret 1990', NULL, 'Aktif', NOW(), NOW()),
(7, 5, 'Dewi Lestari', 'Jakarta, 20 April 1985', NULL, 'Aktif', NOW(), NOW()),
(8, 5, 'Rudi Hartono', 'Surabaya, 10 Mei 1992', NULL, 'Nonaktif', NOW(), NOW());

INSERT INTO tb_jadwal (jdw_wil_id, jdw_keg_id, jdw_ptg_id, jdw_user_id, jdw_tanggal, jdw_jam_mulai, jdw_jam_selesai, jdw_status, jdw_catatan, created_at, updated_at) VALUES
(1, 1, 1, 1, CURDATE() - INTERVAL 6 DAY, '07:00:00', '09:00:00', 'Selesai', NULL, NOW(), NOW()),
(5, 2, 7, 1, CURDATE() - INTERVAL 3 DAY, '06:00:00', '08:00:00', 'Selesai', NULL, NOW(), NOW()),
(2, 3, 3, 1, CURDATE() - INTERVAL 1 DAY, '07:30:00', '10:00:00', 'Dibatalkan', 'Dibatalkan karena hujan deras.', NOW(), NOW()),
(3, 1, 5, 1, CURDATE(), '07:00:00', '09:00:00', 'Terjadwal', NULL, NOW(), NOW()),
(4, 4, 6, 1, CURDATE(), '15:00:00', '17:00:00', 'Terjadwal', NULL, NOW(), NOW()),
(1, 2, 2, 1, CURDATE() + INTERVAL 2 DAY, '06:30:00', '08:00:00', 'Terjadwal', NULL, NOW(), NOW()),
(2, 3, 4, 1, CURDATE() + INTERVAL 4 DAY, '07:00:00', '10:00:00', 'Terjadwal', NULL, NOW(), NOW()),
(5, 1, 7, 1, CURDATE() + INTERVAL 6 DAY, '06:00:00', '09:00:00', 'Terjadwal', NULL, NOW(), NOW());
