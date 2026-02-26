@extends('layouts.app')

@section('title', 'Register - Smart Leave Management')

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
                <h1 class="text-5xl font-bold text-white mb-4">Join Our Team</h1>
                <p class="text-xl text-blue-100">Create your account to get started</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 mt-8">
                <h3 class="text-white font-semibold mb-4">Why Choose Smart Leave?</h3>
                <ul class="text-blue-100 text-sm space-y-3 text-left">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-3 text-green-400"></i>
                        <span>Streamlined leave request process</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-3 text-green-400"></i>
                        <span>Real-time approval tracking</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-3 text-green-400"></i>
                        <span>Comprehensive audit trail</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-3 text-green-400"></i>
                        <span>Enterprise-grade security</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Panel - Register Form -->
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
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-600">Fill in your information to get started</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <span class="text-red-700 text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="text-green-700 text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input
                            type="text"
                            name="name"
                            required
                            value="{{ old('name') }}"
                            class="input-field pl-10"
                            placeholder="John Doe"
                        >
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-building"></i>
                        </span>
                        <select name="department" required class="input-field pl-10">
                            <option value="">Select Department</option>
                            <option value="engineering" {{ old('department') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="marketing" {{ old('department') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="finance" {{ old('department') == 'finance' ? 'selected' : '' }}>Finance</option>
                            <option value="hr" {{ old('department') == 'hr' ? 'selected' : '' }}>Human Resources</option>
                            <option value="operations" {{ old('department') == 'operations' ? 'selected' : '' }}>Operations</option>
                            <option value="sales" {{ old('department') == 'sales' ? 'selected' : '' }}>Sales</option>
                        </select>
                    </div>
                    @error('department')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-user-tag"></i>
                        </span>
                        <select name="role" required class="input-field pl-10">
                            <option value="">Select Role</option>
                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    @error('role')
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
                            minlength="8"
                            class="input-field pl-10"
                            placeholder="Minimum 8 characters"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="input-field pl-10"
                            placeholder="Re-enter password"
                        >
                    </div>
                </div>

                <div class="flex items-start">
                    <input type="checkbox" required class="w-4 h-4 mt-1 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-600">
                        I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                    </span>
                </div>

                <button type="submit" class="w-full btn-primary py-3 rounded-lg font-medium text-base">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-dark">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
