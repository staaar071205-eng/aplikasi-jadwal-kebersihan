@extends('layouts.app')

@section('title', 'Wilayah')

@section('content')
<h2>Wilayah</h2>

<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <a href="{{ route('wilayah.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Input Wilayah</a>

    <form method="get" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari wilayah...">
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        @if ($q !== '')
            <a href="{{ route('wilayah.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th style="width:60px">NO</th>
                <th>NAMA WILAYAH</th>
                <th>KETERANGAN</th>
                <th class="text-center">PETUGAS</th>
                <th class="text-center">JADWAL</th>
                <th style="width:170px">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $rows->firstItem() + $loop->index }}</td>
                    <td>{{ $row->wil_nama }}</td>
                    <td>{{ $row->wil_keterangan }}</td>
                    <td class="text-center">{{ $row->petugas_count }}</td>
                    <td class="text-center">{{ $row->jadwal_count }}</td>
                    <td>
                        <a href="{{ route('wilayah.edit', $row) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('wilayah.destroy', $row) }}" method="post" class="d-inline"
                            data-konfirmasi="Hapus wilayah {{ $row->wil_nama }}?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data wilayah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $rows->links() }}
@endsection
