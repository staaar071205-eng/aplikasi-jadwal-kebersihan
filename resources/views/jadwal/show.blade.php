@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
<h2>Detail Jadwal Kebersihan</h2>

<div class="row">
    <div class="col-lg-7">
        <table class="table table-bordered">
            <tr><th style="width:200px">Tanggal</th><td>{{ $jadwal->jdw_tanggal->translatedFormat('l, d F Y') }}</td></tr>
            <tr><th>Jam</th><td>{{ $jadwal->jamMulai() }} - {{ $jadwal->jamSelesai() }} WIB</td></tr>
            <tr><th>Wilayah</th><td>{{ $jadwal->wilayah->wil_nama }}</td></tr>
            <tr><th>Jenis Kegiatan</th><td>{{ $jadwal->kegiatan->keg_nama }}</td></tr>
            <tr>
                <th>Petugas</th>
                <td>
                    {{ $jadwal->petugas->ptg_nama }}
                    @if ($jadwal->petugas->ptg_Tempat_&_Tanggal_Lahir)
                        <span class="text-muted">({{ $jadwal->petugas->ptg_Tempat_&_Tanggal_Lahir }})</span>
                    @endif
                </td>
            </tr>
            <tr><th>Status</th><td><span class="badge {{ $jadwal->badge() }}">{{ $jadwal->jdw_status }}</span></td></tr>
            <tr><th>Catatan</th><td>{!! nl2br(e($jadwal->jdw_catatan ?: '-')) !!}</td></tr>
            <tr><th>Dicatat oleh</th><td>{{ $jadwal->pencatat->user_nama }}</td></tr>
            <tr><th>Dibuat</th><td>{{ $jadwal->created_at?->translatedFormat('d M Y H:i') }}</td></tr>
            <tr><th>Diperbarui</th><td>{{ $jadwal->updated_at?->translatedFormat('d M Y H:i') }}</td></tr>
        </table>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Foto Bukti Kegiatan</div>
            <div class="card-body text-center">
                @if ($jadwal->jdw_foto)
                    <a href="{{ asset('uploads/jadwal/'.$jadwal->jdw_foto) }}" target="_blank">
                        <img src="{{ asset('uploads/jadwal/'.$jadwal->jdw_foto) }}" alt="Foto bukti" class="foto-detail img-thumbnail">
                    </a>
                @else
                    <span class="text-muted">Belum ada foto bukti.</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    @if ($jadwal->jdw_status === 'Terjadwal')
        <form action="{{ route('jadwal.selesai', $jadwal) }}" method="post" class="d-inline">
            @csrf
            @method('PATCH')
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Tandai Selesai</button>
        </form>
    @endif
    <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
