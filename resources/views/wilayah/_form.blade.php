<div class="mb-3">
    <label for="wil_nama" class="form-label">Nama Wilayah <span class="text-danger">*</span></label>
    <input type="text" id="wil_nama" name="wil_nama" maxlength="100"
        class="form-control @error('wil_nama') is-invalid @enderror"
        value="{{ old('wil_nama', $wilayah->wil_nama) }}" required>
    @error('wil_nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="wil_keterangan" class="form-label">Keterangan</label>
    <textarea id="wil_keterangan" name="wil_keterangan" rows="4" maxlength="1000"
        class="form-control @error('wil_keterangan') is-invalid @enderror">{{ old('wil_keterangan', $wilayah->wil_keterangan) }}</textarea>
    @error('wil_keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
