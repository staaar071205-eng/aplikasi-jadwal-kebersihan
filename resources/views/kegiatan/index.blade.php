@extends('layouts.app')

@section('title', 'Jenis Kegiatan')

@section('content')
<h2>Jenis Kegiatan</h2>

<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Input Jenis Kegiatan</a>

    <form method="get" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari kegiatan...">
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        @if ($q !== '')
            <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th style="width:60px">NO</th>
                <th>NAMA KEGIATAN</th>
                <th>KETERANGAN</th>
                <th class="text-center">JADWAL</th>
                <th style="width:170px">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $rows->firstItem() + $loop->index }}</td>
                    <td>{{ $row->keg_nama }}</td>
                    <td>{{ $row->keg_keterangan }}</td>
                    <td class="text-center">{{ $row->jadwal_count }}</td>
                    <td>
                        <a href="{{ route('kegiatan.edit', $row) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('kegiatan.destroy', $row) }}" method="post" class="d-inline"
                            data-konfirmasi="Hapus jenis kegiatan {{ $row->keg_nama }}?">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data jenis kegiatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $rows->links() }}
@endsection
