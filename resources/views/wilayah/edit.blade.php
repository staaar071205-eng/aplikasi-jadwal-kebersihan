@extends('layouts.app')

@section('title', 'Edit Wilayah')

@section('content')
<h2>Edit Wilayah</h2>

<form action="{{ route('wilayah.update', $wilayah) }}" method="post" class="col-lg-8 px-0">
    @csrf
    @method('PUT')
    @include('wilayah._form')
    <button type="submit" class="btn btn-primary">UPDATE</button>
    <a href="{{ route('wilayah.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
