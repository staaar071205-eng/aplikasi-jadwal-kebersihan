@extends('layouts.app')

@section('title', 'Jadwal Kebersihan')

@section('content')
<h2>Jadwal Kebersihan</h2>

<a href="{{ route('jadwal.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Input Jadwal</a>

<form method="get" class="card card-body border-0 shadow-sm mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label mb-1">Cari</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Kegiatan, wilayah, petugas...">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Wilayah</label>
            <select name="wilayah" class="form-select">
                <option value="">Semua</option>
                @foreach ($listWilayah as $w)
                    <option value="{{ $w->wil_id }}" @selected($wilayahId == $w->wil_id)>{{ $w->wil_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                @foreach (\App\Models\Jadwal::STATUS as $st)
                    <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Dari tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Sampai tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
        </div>
        <div class="col-md-1 d-flex gap-1">
            <button class="btn btn-outline-primary" title="Cari"><i class="bi bi-search"></i></button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th style="width:60px">NO</th>
                <th>TANGGAL</th>
                <th>JAM</th>
                <th>WILAYAH</th>
                <th>KEGIATAN</th>
                <th>PETUGAS</th>
                <th>STATUS</th>
                <th>FOTO</th>
                <th style="width:250px">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $rows->firstItem() + $loop->index }}</td>
                    <td>{{ $row->jdw_tanggal->translatedFormat('d M Y') }}</td>
                    <td>{{ $row->jamMulai() }} - {{ $row->jamSelesai() }}</td>
                    <td>{{ $row->wilayah->wil_nama }}</td>
                    <td>{{ $row->kegiatan->keg_nama }}</td>
                    <td>{{ $row->petugas?->ptg_nama ?? 'Petugas telah dihapus' }}</td>
                    <td><span class="badge {{ $row->badge() }}">{{ $row->jdw_status }}</span></td>
                    <td>
                        @if ($row->jdw_foto)
                            <img src="{{ asset('uploads/jadwal/'.$row->jdw_foto) }}" alt="Foto" class="foto-thumb">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($row->jdw_status === 'Terjadwal')
                            <form action="{{ route('jadwal.selesai', $row) }}" method="post" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-success" title="Tandai selesai"><i class="bi bi-check-lg"></i></button>
                            </form>
                        @endif
                        <a href="{{ route('jadwal.show', $row) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('jadwal.edit', $row) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('jadwal.destroy', $row) }}" method="post" class="d-inline"
                            data-konfirmasi="Hapus jadwal {{ $row->kegiatan->keg_nama }} tanggal {{ $row->jdw_tanggal->format('d/m/Y') }}?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Belum ada data jadwal kebersihan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $rows->links() }}
@endsection
