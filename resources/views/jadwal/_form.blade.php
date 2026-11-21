<div class="row">
    <div class="col-md-6 mb-3">
        <label for="jdw_wil_id" class="form-label">Wilayah <span class="text-danger">*</span></label>
        <select id="jdw_wil_id" name="jdw_wil_id" class="form-select @error('jdw_wil_id') is-invalid @enderror" required>
            <option value="">-- Pilih wilayah --</option>
            @foreach ($listWilayah as $w)
                <option value="{{ $w->wil_id }}" @selected(old('jdw_wil_id', $jadwal->jdw_wil_id) == $w->wil_id)>{{ $w->wil_nama }}</option>
            @endforeach
        </select>
        @error('jdw_wil_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="jdw_keg_id" class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
        <select id="jdw_keg_id" name="jdw_keg_id" class="form-select @error('jdw_keg_id') is-invalid @enderror" required>
            <option value="">-- Pilih kegiatan --</option>
            @foreach ($listKegiatan as $k)
                <option value="{{ $k->keg_id }}" @selected(old('jdw_keg_id', $jadwal->jdw_keg_id) == $k->keg_id)>{{ $k->keg_nama }}</option>
            @endforeach
        </select>
        @error('jdw_keg_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="jdw_ptg_id" class="form-label">Petugas Penanggung Jawab <span class="text-danger">*</span></label>
    <select id="jdw_ptg_id" name="jdw_ptg_id" class="form-select @error('jdw_ptg_id') is-invalid @enderror" required>
        <option value="">-- Pilih petugas --</option>
        @foreach ($listPetugas->groupBy(fn ($p) => $p->wilayah->wil_nama) as $namaWilayah => $kelompok)
            <optgroup label="{{ $namaWilayah }}">
                @foreach ($kelompok as $p)
                    <option value="{{ $p->ptg_id }}" @selected(old('jdw_ptg_id', $jadwal->jdw_ptg_id) == $p->ptg_id)>
                        {{ $p->ptg_nama }}{{ $p->ptg_status === 'Nonaktif' ? ' (nonaktif)' : '' }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @error('jdw_ptg_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="jdw_tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
        <input type="date" id="jdw_tanggal" name="jdw_tanggal"
            class="form-control @error('jdw_tanggal') is-invalid @enderror"
            value="{{ old('jdw_tanggal', $jadwal->jdw_tanggal?->format('Y-m-d')) }}" required>
        @error('jdw_tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="jdw_jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
        <input type="time" id="jdw_jam_mulai" name="jdw_jam_mulai"
            class="form-control @error('jdw_jam_mulai') is-invalid @enderror"
            value="{{ old('jdw_jam_mulai', $jadwal->jamMulai()) }}" required>
        @error('jdw_jam_mulai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="jdw_jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
        <input type="time" id="jdw_jam_selesai" name="jdw_jam_selesai"
            class="form-control @error('jdw_jam_selesai') is-invalid @enderror"
            value="{{ old('jdw_jam_selesai', $jadwal->jamSelesai()) }}" required>
        @error('jdw_jam_selesai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="jdw_status" class="form-label">Status <span class="text-danger">*</span></label>
    <select id="jdw_status" name="jdw_status" class="form-select @error('jdw_status') is-invalid @enderror" required>
        @foreach (\App\Models\Jadwal::STATUS as $st)
            <option value="{{ $st }}" @selected(old('jdw_status', $jadwal->jdw_status) === $st)>{{ $st }}</option>
        @endforeach
    </select>
    @error('jdw_status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="jdw_catatan" class="form-label">Catatan</label>
    <textarea id="jdw_catatan" name="jdw_catatan" rows="3" maxlength="1000"
        class="form-control @error('jdw_catatan') is-invalid @enderror"
        placeholder="Contoh: bawa sapu lidi dan karung sampah">{{ old('jdw_catatan', $jadwal->jdw_catatan) }}</textarea>
    @error('jdw_catatan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="jdw_foto" class="form-label">Foto Bukti Kegiatan</label>

    @if ($jadwal->jdw_foto)
        <div class="mb-2">
            <img src="{{ asset('uploads/jadwal/'.$jadwal->jdw_foto) }}" alt="Foto" class="img-thumbnail" style="max-height:140px">
            <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapus_foto">
                <label class="form-check-label" for="hapus_foto">Hapus foto ini</label>
            </div>
        </div>
    @endif

    <input type="file" id="jdw_foto" name="jdw_foto" accept="image/png,image/jpeg,image/webp"
        class="form-control @error('jdw_foto') is-invalid @enderror">
    <div class="form-text">Opsional. JPG, PNG, atau WEBP, maksimal 2 MB. {{ $jadwal->jdw_foto ? 'Kosongkan jika tidak ingin mengganti foto.' : '' }}</div>
    @error('jdw_foto')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <img id="preview-foto" src="" alt="Pratinjau" class="img-thumbnail mt-2 d-none" style="max-height:140px">
</div>
