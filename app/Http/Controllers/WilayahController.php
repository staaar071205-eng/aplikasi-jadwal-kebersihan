<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WilayahController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));

        $rows = Wilayah::withCount(['petugas', 'jadwal'])
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('wil_nama', 'like', "%{$q}%")
                    ->orWhere('wil_keterangan', 'like', "%{$q}%");
            }))
            ->orderBy('wil_nama')
            ->paginate(10)
            ->withQueryString();

        return view('wilayah.index', compact('rows', 'q'));
    }

    public function create(): View
    {
        return view('wilayah.create', ['wilayah' => new Wilayah]);
    }

    public function store(Request $request): RedirectResponse
    {
        Wilayah::create($this->validasi($request));

        return redirect()->route('wilayah.index')->with('sukses', 'Wilayah berhasil ditambahkan.');
    }

    public function edit(Wilayah $wilayah): View
    {
        return view('wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, Wilayah $wilayah): RedirectResponse
    {
        $wilayah->update($this->validasi($request, $wilayah));

        return redirect()->route('wilayah.index')->with('sukses', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(Wilayah $wilayah): RedirectResponse
    {
        if ($wilayah->petugas()->exists() || $wilayah->jadwal()->exists()) {
            return redirect()->route('wilayah.index')
                ->with('gagal', 'Wilayah "'.$wilayah->wil_nama.'" tidak dapat dihapus karena masih dipakai data petugas atau jadwal.');
        }

        $wilayah->delete();

        return redirect()->route('wilayah.index')->with('sukses', 'Wilayah berhasil dihapus.');
    }

    private function validasi(Request $request, ?Wilayah $wilayah = null): array
    {
        return $request->validate([
            'wil_nama' => [
                'required', 'string', 'max:100',
                Rule::unique('tb_wilayah', 'wil_nama')->ignore($wilayah?->wil_id, 'wil_id'),
            ],
            'wil_keterangan' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
