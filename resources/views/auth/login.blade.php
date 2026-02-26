@extends('layouts.app')

@section('title', 'Login - Smart Leave Management')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-primary items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center px-12">
            <div class="mb-8">
                <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-building text-white text-5xl"></i>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">Smart Leave</h1>
                <p class="text-xl text-blue-100">Enterprise Leave Management System</p>
            </div>

            <div class="grid grid-cols-3 gap-6 mt-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                    <i class="fas fa-user-check text-white text-2xl mb-3"></i>
                    <p class="text-white text-sm">Multi-role<br>Access</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                    <i class="fas fa-tasks text-white text-2xl mb-3"></i>
                    <p class="text-white text-sm">Workflow<br>Automation</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                    <i class="fas fa-shield-alt text-white text-2xl mb-3"></i>
                    <p class="text-white text-sm">Secure &<br>Compliant</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-12">
        <div class="max-w-md w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-primary">Smart Leave</h1>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to access your dashboard</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <span class="text-red-700 text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            required
                            value="{{ old('email') }}"
                            class="input-field pl-10"
                            placeholder="you@company.com"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            required
                            class="input-field pl-10"
                            placeholder="••••••••"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="w-full btn-primary py-3 rounded-lg font-medium text-base">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-primary hover:text-primary-dark">
                        Create one now
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
