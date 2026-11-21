@extends('layouts.app')

@section('title', 'Input Wilayah')

@section('content')
<h2>Input Wilayah</h2>

<form action="{{ route('wilayah.store') }}" method="post" class="col-lg-8 px-0">
    @csrf
    @include('wilayah._form')
    <button type="submit" class="btn btn-primary">SIMPAN</button>
    <a href="{{ route('wilayah.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
