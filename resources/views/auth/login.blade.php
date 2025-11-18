@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-2xl font-bold text-black">Welcome Back</h2>
        <p class="text-gray-500 mt-1">Sign in to your account to continue</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Field -->
        <div class="form-control">
            <label for="email" class="label">
                <span class="label-text text-black font-semibold">Email Address</span>
            </label>
            <input
                id="email"
                type="email"
                class="input input-bordered w-full @error('email') input-error @enderror"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="Enter your email"
            >
            @error('email')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-control">
            <label for="password" class="label">
                <span class="label-text text-black font-semibold">Password</span>
            </label>
            <input
                id="password"
                type="password"
                class="input input-bordered w-full @error('password') input-error @enderror"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            >
            @error('password')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="label cursor-pointer justify-start gap-2 p-0">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="checkbox checkbox-primary checkbox-sm"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <span class="label-text text-gray-700">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-dark">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Sign In
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark font-semibold">
                        Create one now
                    </a>
                </p>
            </div>
        @endif
    </form>
</div>
@endsection
