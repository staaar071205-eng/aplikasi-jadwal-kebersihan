@extends('layouts.app')

@section('title', 'Edit Jenis Kegiatan')

@section('content')
<h2>Edit Jenis Kegiatan</h2>

<form action="{{ route('kegiatan.update', $kegiatan) }}" method="post" class="col-lg-8 px-0">
    @csrf
    @method('PUT')
    @include('kegiatan._form')
    <button type="submit" class="btn btn-primary">UPDATE</button>
    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
