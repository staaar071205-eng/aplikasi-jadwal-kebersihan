<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $hariIni = today();

        $ringkasan = [
            'wilayah' => Wilayah::count(),
            'petugas' => Petugas::where('ptg_status', 'Aktif')->count(),
            'hariIni' => Jadwal::whereDate('jdw_tanggal', $hariIni)->count(),
            'bulanIni' => Jadwal::whereYear('jdw_tanggal', $hariIni->year)
                ->whereMonth('jdw_tanggal', $hariIni->month)
                ->count(),
        ];

        $jadwalHariIni = Jadwal::with(['wilayah', 'kegiatan', 'petugas'])
            ->whereDate('jdw_tanggal', $hariIni)
            ->orderBy('jdw_jam_mulai')
            ->get();

        $jadwalMendatang = Jadwal::with(['wilayah', 'kegiatan', 'petugas'])
            ->whereDate('jdw_tanggal', '>', $hariIni)
            ->whereDate('jdw_tanggal', '<=', $hariIni->copy()->addDays(7))
            ->where('jdw_status', 'Terjadwal')
            ->orderBy('jdw_tanggal')
            ->orderBy('jdw_jam_mulai')
            ->get();

        $perStatus = Jadwal::selectRaw('jdw_status, COUNT(*) as total')
            ->groupBy('jdw_status')
            ->pluck('total', 'jdw_status');

        return view('dashboard.index', compact('ringkasan', 'jadwalHariIni', 'jadwalMendatang', 'perStatus'));
    }
}
