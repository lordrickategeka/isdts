@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-2xl font-bold text-black">Create Account</h2>
        <p class="text-gray-500 mt-1">Sign up to get started</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name Field -->
        <div class="form-control">
            <label for="name" class="label">
                <span class="label-text text-black font-semibold">Full Name</span>
            </label>
            <input
                id="name"
                type="text"
                class="input input-bordered w-full @error('name') input-error @enderror"
                name="name"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                autofocus
                placeholder="Enter your full name"
            >
            @error('name')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

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
                autocomplete="new-password"
                placeholder="Enter your password"
            >
            @error('password')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="form-control">
            <label for="password-confirm" class="label">
                <span class="label-text text-black font-semibold">Confirm Password</span>
            </label>
            <input
                id="password-confirm"
                type="password"
                class="input input-bordered w-full"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
            >
        </div>

        <!-- Terms & Conditions -->
        <div class="form-control">
            <label class="label cursor-pointer justify-start gap-2 p-0">
                <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" required>
                <span class="label-text text-gray-700">
                    I agree to the
                    <a href="#" class="text-primary hover:text-primary-dark">Terms & Conditions</a>
                </span>
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Create Account
        </button>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary hover:text-primary-dark font-semibold">
                    Sign in
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
