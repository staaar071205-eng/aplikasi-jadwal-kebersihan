@extends('layouts.app')

@section('title', 'Input Jenis Kegiatan')

@section('content')
<h2>Input Jenis Kegiatan</h2>

<form action="{{ route('kegiatan.store') }}" method="post" class="col-lg-8 px-0">
    @csrf
    @include('kegiatan._form')
    <button type="submit" class="btn btn-primary">SIMPAN</button>
    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
