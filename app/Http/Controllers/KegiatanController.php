<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));

        $rows = Kegiatan::withCount('jadwal')
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('keg_nama', 'like', "%{$q}%")
                    ->orWhere('keg_keterangan', 'like', "%{$q}%");
            }))
            ->orderBy('keg_nama')
            ->paginate(10)
            ->withQueryString();

        return view('kegiatan.index', compact('rows', 'q'));
    }

    public function create(): View
    {
        return view('kegiatan.create', ['kegiatan' => new Kegiatan]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kegiatan::create($this->validasi($request));

        return redirect()->route('kegiatan.index')->with('sukses', 'Jenis kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->update($this->validasi($request, $kegiatan));

        return redirect()->route('kegiatan.index')->with('sukses', 'Jenis kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->jadwal()->exists()) {
            return redirect()->route('kegiatan.index')
                ->with('gagal', 'Jenis kegiatan "'.$kegiatan->keg_nama.'" tidak dapat dihapus karena masih dipakai data jadwal.');
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('sukses', 'Jenis kegiatan berhasil dihapus.');
    }

    private function validasi(Request $request, ?Kegiatan $kegiatan = null): array
    {
        return $request->validate([
            'keg_nama' => [
                'required', 'string', 'max:100',
                Rule::unique('tb_kegiatan', 'keg_nama')->ignore($kegiatan?->keg_id, 'keg_id'),
            ],
            'keg_keterangan' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
