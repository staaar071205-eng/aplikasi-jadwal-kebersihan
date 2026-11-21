@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>

<div class="alert alert-info">
    Selamat datang, <strong>{{ auth()->user()->user_nama }}</strong>.
    Hari ini {{ today()->translatedFormat('l, d F Y') }}.
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted"><i class="bi bi-calendar-event"></i> Jadwal Hari Ini</div>
                <div class="display-6">{{ $ringkasan['hariIni'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted"><i class="bi bi-calendar-month"></i> Jadwal Bulan Ini</div>
                <div class="display-6">{{ $ringkasan['bulanIni'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted"><i class="bi bi-geo-alt"></i> Wilayah</div>
                <div class="display-6">{{ $ringkasan['wilayah'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted"><i class="bi bi-people"></i> Petugas Aktif</div>
                <div class="display-6">{{ $ringkasan['petugas'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <span class="me-2 text-muted">Seluruh jadwal:</span>
    @foreach (\App\Models\Jadwal::STATUS as $st)
        <span class="badge {{ $st === 'Selesai' ? 'bg-success' : ($st === 'Dibatalkan' ? 'bg-secondary' : 'bg-primary') }} me-1">
            {{ $st }}: {{ $perStatus[$st] ?? 0 }}
        </span>
    @endforeach
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-calendar-event"></i> Jadwal Hari Ini
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>Wilayah</th>
                    <th>Kegiatan</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalHariIni as $j)
                    <tr>
                        <td>{{ $j->jamMulai() }} - {{ $j->jamSelesai() }}</td>
                        <td>{{ $j->wilayah->wil_nama }}</td>
                        <td>{{ $j->kegiatan->keg_nama }}</td>
                        <td>{{ $j->petugas->ptg_nama }}</td>
                        <td><span class="badge {{ $j->badge() }}">{{ $j->jdw_status }}</span></td>
                        <td class="text-end">
                            @if ($j->jdw_status === 'Terjadwal')
                                <form action="{{ route('jadwal.selesai', $j) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="Tandai selesai">
                                        <i class="bi bi-check-lg"></i> Selesai
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('jadwal.show', $j) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Tidak ada jadwal kebersihan hari ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-calendar-week"></i> 7 Hari Mendatang
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Wilayah</th>
                    <th>Kegiatan</th>
                    <th>Petugas</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalMendatang as $j)
                    <tr>
                        <td>{{ $j->jdw_tanggal->translatedFormat('l, d M Y') }}</td>
                        <td>{{ $j->jamMulai() }} - {{ $j->jamSelesai() }}</td>
                        <td>{{ $j->wilayah->wil_nama }}</td>
                        <td>{{ $j->kegiatan->keg_nama }}</td>
                        <td>{{ $j->petugas->ptg_nama }}</td>
                        <td class="text-end"><a href="{{ route('jadwal.show', $j) }}" class="btn btn-sm btn-info">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Belum ada jadwal untuk 7 hari ke depan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
