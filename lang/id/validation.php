<?php

/*
 * Pesan validasi Bahasa Indonesia (hanya aturan yang dipakai aplikasi ini).
 * Aturan lain otomatis memakai bahasa cadangan (en).
 */
return [
    'after' => ':attribute harus berisi waktu setelah :date.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_format' => ':attribute tidak cocok dengan format :format.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'max' => [
        'array' => ':attribute tidak boleh lebih dari :max item.',
        'file' => 'Ukuran :attribute tidak boleh lebih dari :max kilobita.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'mimes' => ':attribute harus berupa berkas bertipe: :values.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',

    'attributes' => [
        'user_email' => 'Email',
        'password' => 'Password',
        'wil_nama' => 'Nama wilayah',
        'wil_keterangan' => 'Keterangan',
        'keg_nama' => 'Nama kegiatan',
        'keg_keterangan' => 'Keterangan',
        'ptg_wil_id' => 'Wilayah',
        'ptg_nama' => 'Nama petugas',
        'ptg_Tempat_&_Tanggal_Lahir' => 'Tempat & Tanggal Lahir',
        'ptg_alamat' => 'Alamat',
        'ptg_status' => 'Status',
        'jdw_wil_id' => 'Wilayah',
        'jdw_keg_id' => 'Jenis kegiatan',
        'jdw_ptg_id' => 'Petugas',
        'jdw_tanggal' => 'Tanggal',
        'jdw_jam_mulai' => 'Jam mulai',
        'jdw_jam_selesai' => 'Jam selesai',
        'jdw_status' => 'Status',
        'jdw_catatan' => 'Catatan',
        'jdw_foto' => 'Foto',
    ],
];
