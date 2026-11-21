<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Petugas;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /** Folder penyimpanan foto bukti (di dalam public/) */
    private const FOLDER_FOTO = 'uploads/jadwal';

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $status = $request->query('status');
        $wilayahId = $request->query('wilayah');
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $rows = Jadwal::with(['wilayah', 'kegiatan', 'petugas'])
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('jdw_catatan', 'like', "%{$q}%")
                    ->orWhereHas('kegiatan', fn ($r) => $r->where('keg_nama', 'like', "%{$q}%"))
                    ->orWhereHas('wilayah', fn ($r) => $r->where('wil_nama', 'like', "%{$q}%"))
                    ->orWhereHas('petugas', fn ($r) => $r->where('ptg_nama', 'like', "%{$q}%"));
            }))
            ->when(in_array($status, Jadwal::STATUS, true), fn ($query) => $query->where('jdw_status', $status))
            ->when($wilayahId, fn ($query) => $query->where('jdw_wil_id', $wilayahId))
            ->when($dari, fn ($query) => $query->whereDate('jdw_tanggal', '>=', $dari))
            ->when($sampai, fn ($query) => $query->whereDate('jdw_tanggal', '<=', $sampai))
            ->orderByDesc('jdw_tanggal')
            ->orderBy('jdw_jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $listWilayah = Wilayah::orderBy('wil_nama')->get();

        return view('jadwal.index', compact('rows', 'q', 'status', 'wilayahId', 'dari', 'sampai', 'listWilayah'));
    }

    public function create(): View
    {
        return view('jadwal.create', array_merge($this->pilihan(), [
            'jadwal' => new Jadwal([
                'jdw_tanggal' => today(),
                'jdw_jam_mulai' => '07:00:00',
                'jdw_jam_selesai' => '09:00:00',
                'jdw_status' => 'Terjadwal',
            ]),
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);
        $this->cekBentrok($data);

        $data['jdw_user_id'] = Auth::id();
        $data['jdw_foto'] = $this->simpanFoto($request);

        Jadwal::create($data);

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal kebersihan berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal): View
    {
        $jadwal->load(['wilayah', 'kegiatan', 'petugas', 'pencatat']);

        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal): View
    {
        return view('jadwal.edit', array_merge($this->pilihan($jadwal), compact('jadwal')));
    }

    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $data = $this->validasi($request);
        $this->cekBentrok($data, $jadwal);

        // Foto lama dipertahankan jika tidak ada foto baru
        if ($request->hasFile('jdw_foto')) {
            $this->hapusFoto($jadwal->jdw_foto);
            $data['jdw_foto'] = $this->simpanFoto($request);
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusFoto($jadwal->jdw_foto);
            $data['jdw_foto'] = null;
        }

        $jadwal->update($data);

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal kebersihan berhasil diperbarui.');
    }

    /** Tandai jadwal sebagai selesai dengan satu klik. */
    public function selesai(Jadwal $jadwal): RedirectResponse
    {
        $jadwal->update(['jdw_status' => 'Selesai']);

        return back()->with('sukses', 'Jadwal ditandai selesai.');
    }

    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        $this->hapusFoto($jadwal->jdw_foto);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal kebersihan berhasil dihapus.');
    }

    /* ------------------------------------------------------------------ */

    /** Data pilihan untuk dropdown pada form. */
    private function pilihan(?Jadwal $jadwal = null): array
    {
        // Petugas aktif, ditambah petugas yang sedang dipakai jadwal ini (walau sudah nonaktif)
        $listPetugas = Petugas::with('wilayah')
            ->where(function ($q) use ($jadwal) {
                $q->where('ptg_status', 'Aktif');
                if ($jadwal) {
                    $q->orWhere('ptg_id', $jadwal->jdw_ptg_id);
                }
            })
            ->orderBy('ptg_nama')
            ->get();

        return [
            'listWilayah' => Wilayah::orderBy('wil_nama')->get(),
            'listKegiatan' => Kegiatan::orderBy('keg_nama')->get(),
            'listPetugas' => $listPetugas,
        ];
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'jdw_wil_id' => ['required', 'integer', Rule::exists('tb_wilayah', 'wil_id')],
            'jdw_keg_id' => ['required', 'integer', Rule::exists('tb_kegiatan', 'keg_id')],
            'jdw_ptg_id' => ['nullable', 'integer', Rule::exists('tb_petugas', 'ptg_id')],
            'jdw_tanggal' => ['required', 'date'],
            'jdw_jam_mulai' => ['required', 'date_format:H:i'],
            'jdw_jam_selesai' => ['required', 'date_format:H:i', 'after:jdw_jam_mulai'],
            'jdw_status' => ['required', Rule::in(Jadwal::STATUS)],
            'jdw_catatan' => ['nullable', 'string', 'max:1000'],
            'jdw_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Simpan jam dalam format HH:MM:SS
        $data['jdw_jam_mulai'] .= ':00';
        $data['jdw_jam_selesai'] .= ':00';

        // File ditangani terpisah
        unset($data['jdw_foto']);

        return $data;
    }

    /**
     * Seorang petugas tidak boleh memiliki dua jadwal yang waktunya saling tumpang tindih
     * pada tanggal yang sama (jadwal berstatus Dibatalkan diabaikan).
     */
    private function cekBentrok(array $data, ?Jadwal $abaikan = null): void
    {
        if ($data['jdw_status'] === 'Dibatalkan') {
            return;
        }

        $bentrok = Jadwal::where('jdw_ptg_id', $data['jdw_ptg_id'])
            ->whereDate('jdw_tanggal', $data['jdw_tanggal'])
            ->where('jdw_status', '!=', 'Dibatalkan')
            ->where('jdw_jam_mulai', '<', $data['jdw_jam_selesai'])
            ->where('jdw_jam_selesai', '>', $data['jdw_jam_mulai'])
            ->when($abaikan, fn ($q) => $q->where('jdw_id', '!=', $abaikan->jdw_id))
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'jdw_ptg_id' => 'Petugas ini sudah memiliki jadwal lain yang waktunya bertabrakan pada tanggal tersebut.',
            ]);
        }
    }

    private function simpanFoto(Request $request): ?string
    {
        if (! $request->hasFile('jdw_foto')) {
            return null;
        }

        $file = $request->file('jdw_foto');
        $nama = 'jadwal_'.now()->format('YmdHis').'_'.Str::random(8).'.'.$file->extension();

        File::ensureDirectoryExists(public_path(self::FOLDER_FOTO));
        $file->move(public_path(self::FOLDER_FOTO), $nama);

        return $nama;
    }

    private function hapusFoto(?string $nama): void
    {
        if ($nama) {
            File::delete(public_path(self::FOLDER_FOTO.'/'.basename($nama)));
        }
    }
}
