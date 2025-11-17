@extends('layouts.guest')

@section('content')

<div class="d-flex justify-content-center align-items-center py-5" style="min-height: 100vh;">

    <div class="w-100" style="max-width: 430px;">

        <div class="card shadow-lg border-0"
             style="border-top: 5px solid #013A63; border-radius: 12px;">

            <div class="card-header text-center text-white"
                 style="background:#013A63; border-radius: 12px 12px 0 0;">
                <h4 class="fw-bold mb-0">Welcome Back</h4>
                <small>Login to continue</small>
            </div>

            <div class="card-body p-4">

                {{-- SESSION STATUS --}}
                @if (session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required autofocus
                               class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password"
                               name="password"
                               required
                               class="form-control @error('password') is-invalid @enderror">

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit"
                                class="btn text-white px-4"
                                style="background:#013A63;">
                            Log In
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection
