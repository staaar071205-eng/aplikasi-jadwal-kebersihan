<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $wilayahId = $request->query('wilayah');
        $status = $request->query('status');

        $rows = Petugas::with('wilayah')
            ->withCount('jadwal')
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('ptg_nama', 'like', "%{$q}%")
                    ->orWhere('ptg_Tempat_&_Tanggal_Lahir', 'like', "%{$q}%")
                    ->orWhere('ptg_alamat', 'like', "%{$q}%");
            }))
            ->when($wilayahId, fn ($query) => $query->where('ptg_wil_id', $wilayahId))
            ->when(in_array($status, Petugas::STATUS, true), fn ($query) => $query->where('ptg_status', $status))
            ->orderBy('ptg_nama')
            ->paginate(10)
            ->withQueryString();

        $listWilayah = Wilayah::orderBy('wil_nama')->get();

        return view('petugas.index', compact('rows', 'q', 'wilayahId', 'status', 'listWilayah'));
    }

    public function create(): View
    {
        return view('petugas.create', [
            'petugas' => new Petugas(['ptg_status' => 'Aktif']),
            'listWilayah' => Wilayah::orderBy('wil_nama')->get(),
        ]);
    }

public function store(Request $request): RedirectResponse
{
    Petugas::create($this->validasi($request));

    return redirect()->route('petugas.index')
        ->with('sukses', 'Petugas berhasil ditambahkan.');
}

    public function edit(Petugas $petugas): View
    {
        return view('petugas.edit', [
            'petugas' => $petugas,
            'listWilayah' => Wilayah::orderBy('wil_nama')->get(),
        ]);
    }

public function update(Request $request, Petugas $petugas): RedirectResponse
{
    $petugas->update($this->validasi($request));

    return redirect()->route('petugas.index')
        ->with('sukses', 'Data petugas berhasil diperbarui.');
}

public function destroy(Petugas $petugas): RedirectResponse
{
    $nama = $petugas->ptg_nama;

    $petugas->delete();

    return redirect()->route('petugas.index')
        ->with('sukses', 'Petugas '.$nama.' berhasil dihapus. Riwayat jadwal tetap tersimpan.');
}

private function validasi(Request $request): array
{
    return $request->validate([
        'ptg_wil_id' => [
            'required',
            'integer',
            Rule::exists('tb_wilayah', 'wil_id')
        ],

        'ptg_nama' => [
            'required',
            'string',
            'max:100'
        ],

        'ptg_Tempat_&_Tanggal_Lahir' => [
            'nullable',
            'string',
            'max:100'
        ],

        'ptg_alamat' => [
            'nullable',
            'string',
            'max:1000'
        ],

        'ptg_status' => [
            'required',
            Rule::in(Petugas::STATUS)
        ],
    ]);
}
}
