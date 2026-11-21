@extends('layouts.app')

@section('title', 'Input Jadwal')

@section('content')
<h2>Input Jadwal Kebersihan</h2>

<form action="{{ route('jadwal.store') }}" method="post" enctype="multipart/form-data" class="col-lg-9 px-0">
    @csrf
    @include('jadwal._form')
    <button type="submit" class="btn btn-primary">SIMPAN</button>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
