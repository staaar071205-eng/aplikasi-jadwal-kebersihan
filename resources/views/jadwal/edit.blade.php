@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<h2>Edit Jadwal Kebersihan</h2>

<form action="{{ route('jadwal.update', $jadwal) }}" method="post" enctype="multipart/form-data" class="col-lg-9 px-0">
    @csrf
    @method('PUT')
    @include('jadwal._form')
    <button type="submit" class="btn btn-primary">UPDATE</button>
    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
