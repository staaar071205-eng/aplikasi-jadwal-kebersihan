<div class="mb-3">
    <label for="keg_nama" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
    <input type="text" id="keg_nama" name="keg_nama" maxlength="100"
        class="form-control @error('keg_nama') is-invalid @enderror"
        value="{{ old('keg_nama', $kegiatan->keg_nama) }}" required>
    @error('keg_nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="keg_keterangan" class="form-label">Keterangan</label>
    <textarea id="keg_keterangan" name="keg_keterangan" rows="4" maxlength="1000"
        class="form-control @error('keg_keterangan') is-invalid @enderror">{{ old('keg_keterangan', $kegiatan->keg_keterangan) }}</textarea>
    @error('keg_keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
