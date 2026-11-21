@extends('layouts.app')

@section('title', 'Input Petugas')

@section('content')
<h2>Input Petugas</h2>

<form action="{{ route('petugas.store') }}" method="post" class="col-lg-8 px-0">
    @csrf
    @include('petugas._form')
    <button type="submit" class="btn btn-primary">SIMPAN</button>
    <a href="{{ route('petugas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
