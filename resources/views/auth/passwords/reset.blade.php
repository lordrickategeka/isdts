@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-2xl font-bold text-black">Reset Password</h2>
        <p class="text-gray-500 mt-1">Enter your new password</p>
    </div>

    <!-- Reset Form -->
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

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
                value="{{ $email ?? old('email') }}"
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
                <span class="label-text text-black font-semibold">New Password</span>
            </label>
            <input
                id="password"
                type="password"
                class="input input-bordered w-full @error('password') input-error @enderror"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Enter new password"
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
                placeholder="Confirm new password"
            >
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Reset Password
        </button>
    </form>
</div>
@endsection
