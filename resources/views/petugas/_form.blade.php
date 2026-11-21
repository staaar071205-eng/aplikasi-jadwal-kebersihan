<div class="mb-3">
    <label for="ptg_wil_id" class="form-label">Wilayah <span class="text-danger">*</span></label>
    <select id="ptg_wil_id" name="ptg_wil_id" class="form-select @error('ptg_wil_id') is-invalid @enderror" required>
        <option value="">-- Pilih wilayah --</option>
        @foreach ($listWilayah as $w)
            <option value="{{ $w->wil_id }}" @selected(old('ptg_wil_id', $petugas->ptg_wil_id) == $w->wil_id)>{{ $w->wil_nama }}</option>
        @endforeach
    </select>
    @error('ptg_wil_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ptg_nama" class="form-label">Nama Petugas <span class="text-danger">*</span></label>
    <input type="text" id="ptg_nama" name="ptg_nama" maxlength="100"
        class="form-control @error('ptg_nama') is-invalid @enderror"
        value="{{ old('ptg_nama', $petugas->ptg_nama) }}" required>
    @error('ptg_nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ptg_Tempat_&_Tanggal_Lahir" class="form-label">Tempat & Tanggal Lahir</label>
    <input type="text" id="ptg_Tempat_&_Tanggal_Lahir" name="ptg_Tempat_&_Tanggal_Lahir" maxlength="100" placeholder="Tempat, Tanggal Lahir"
        class="form-control @error('ptg_Tempat_&_Tanggal_Lahir') is-invalid @enderror"
        value="{{ old('ptg_Tempat_&_Tanggal_Lahir', $petugas->{'ptg_Tempat_&_Tanggal_Lahir'} ?? '') }}">
    @error('ptg_Tempat_&_Tanggal_Lahir')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ptg_alamat" class="form-label">Alamat</label>
    <textarea id="ptg_alamat" name="ptg_alamat" rows="3" maxlength="1000"
        class="form-control @error('ptg_alamat') is-invalid @enderror">{{ old('ptg_alamat', $petugas->ptg_alamat) }}</textarea>
    @error('ptg_alamat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ptg_status" class="form-label">Status <span class="text-danger">*</span></label>
    <select id="ptg_status" name="ptg_status" class="form-select @error('ptg_status') is-invalid @enderror" required>
        @foreach (\App\Models\Petugas::STATUS as $st)
            <option value="{{ $st }}" @selected(old('ptg_status', $petugas->ptg_status) === $st)>{{ $st }}</option>
        @endforeach
    </select>
    @error('ptg_status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
