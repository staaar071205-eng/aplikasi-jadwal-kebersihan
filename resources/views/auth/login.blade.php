@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<main class="py-5 bg-light" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="card shadow-sm border-0 rounded-3 mb-3">
                    <div class="card-header bg-white border-0 text-center py-3">
                        <i class="bi bi-recycle text-primary fs-1"></i>
                        <h4 class="mb-0 fw-semibold">JADWAL KEBERSIHAN</h4>
                        <div class="text-muted">Tuntungan 2</div>
                    </div>

                    <div class="card-body px-4 py-4">
                        @if (session('sukses'))
                            <div class="alert alert-success">{{ session('sukses') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login.proses') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="user_email" class="form-label">Email</label>
                                <input id="user_email" type="email" name="user_email"
                                    class="form-control @error('user_email') is-invalid @enderror"
                                    value="{{ old('user_email') }}" required autofocus autocomplete="username">
                                @error('user_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-muted small mb-0">
                    Akun demo: <code>admin@mail.com</code> / <code>admin</code>
                </p>

            </div>
        </div>
    </div>
</main>
@endsection
