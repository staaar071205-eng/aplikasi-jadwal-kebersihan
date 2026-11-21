@extends('layouts.app')

@section('title', 'Petugas')

@section('content')
<h2>Petugas</h2>

<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <a href="{{ route('petugas.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Input Petugas</a>

    <form method="get" class="d-flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control w-auto" placeholder="Cari nama / Tempat & Tanggal Lahir...">
        <select name="wilayah" class="form-select w-auto">
            <option value="">Semua wilayah</option>
            @foreach ($listWilayah as $w)
                <option value="{{ $w->wil_id }}" @selected($wilayahId == $w->wil_id)>{{ $w->wil_nama }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select w-auto">
            <option value="">Semua status</option>
            @foreach (\App\Models\Petugas::STATUS as $st)
                <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('petugas.index') }}" class="btn btn-outline-secondary">Reset</a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th style="width:60px">NO</th>
                <th>NAMA</th>
                <th>WILAYAH</th>
                <th>Tempat & Tanggal Lahir</th>
                <th>STATUS</th>
                <th class="text-center">JADWAL</th>
                <th style="width:170px">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $rows->firstItem() + $loop->index }}</td>
                    <td>{{ $row->ptg_nama }}</td>
                    <td>{{ $row->wilayah->wil_nama }}</td>
                    <td>{{ $row->getAttribute('ptg_Tempat_&_Tanggal_Lahir') ?: '-' }}</td>
                    <td>
                        <span class="badge {{ $row->ptg_status === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $row->ptg_status }}</span>
                    </td>
                    <td class="text-center">{{ $row->jadwal_count }}</td>
                    <td>
                        <a href="{{ route('petugas.edit', $row) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('petugas.destroy', $row) }}" method="post" class="d-inline"
                            data-konfirmasi="Hapus petugas {{ $row->ptg_nama }}?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data petugas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $rows->links() }}
@endsection
