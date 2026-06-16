@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="text-center mb-5">
            <i class="bi bi-hexagon-fill text-primary" style="font-size: 3.5rem;"></i>
            <h1 class="brand-text mt-3 fs-2">QUANTUM</h1>
            <p class="text-secondary small">Enterprise Inventory Management Solution</p>
        </div>
        
        <div class="glass-card p-5">
            <h3 class="fw-bold text-light mb-4">Welcome back</h3>
            <p class="text-secondary mb-4 small">Please sign in to access the system.</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label text-secondary small fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" 
                           class="form-control form-control-custom @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required autocomplete="email" autofocus 
                           placeholder="name@quantum.com">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-secondary small fw-semibold">Password</label>
                    <input type="password" name="password" id="password" 
                           class="form-control form-control-custom @error('password') is-invalid @enderror" 
                           required autocomplete="current-password" placeholder="••••••••">
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary small" for="remember">
                            Remember my session
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-2">
                    Sign In
                </button>
            </form>
        </div>

        <div class="glass-card mt-4 p-4 text-center border-dashed">
            <div class="text-secondary small mb-2"><i class="bi bi-info-circle me-1"></i> Demo Credentials:</div>
            <div class="d-flex flex-wrap gap-2 justify-content-center" style="font-size: 11px;">
                <span class="badge badge-soft-primary py-1.5 px-2">Admin: admin@quantum.com / password</span>
                <span class="badge badge-soft-primary py-1.5 px-2">Manager: manager@quantum.com / password</span>
                <span class="badge badge-soft-primary py-1.5 px-2">Staff: staff@quantum.com / password</span>
            </div>
        </div>
    </div>
</div>
@endsection
