@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<h2>Edit Petugas</h2>

<form action="{{ route('petugas.update', $petugas) }}" method="post" class="col-lg-8 px-0">
    @csrf
    @method('PUT')
    @include('petugas._form')
    <button type="submit" class="btn btn-primary">UPDATE</button>
    <a href="{{ route('petugas.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
