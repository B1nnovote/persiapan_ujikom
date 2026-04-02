@extends('layouts.auth')

@section('content')
<br><br><br>
<div class="container mt-5 ">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    <!-- Gambar Kiri -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="{{ asset('assets/parkiran/parkir.png') }}" alt="Gambar Parkiran" class="img-fluid h-100 w-100" style="object-fit: cover;">
                    </div>

                    <!-- Form Login -->
                    <div class="col-md-6 p-4">
                        <h4 class="text-center mb-4">Login</h4>

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autofocus>

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required>

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input"
                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="mt-3 text-center">
                                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
