@extends('layouts.app')

@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your account settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-900">Profile</span>
    </div>

    <!-- Profile Header -->
    <div class="card mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user-circle text-primary mr-2"></i>
                Profile Information
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex items-start space-x-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center text-white text-3xl font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ auth()->user()->name }}"
                                required
                                class="input-field"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ auth()->user()->email }}"
                                required
                                class="input-field"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input
                                type="text"
                                value="{{ auth()->user()->department ?? 'N/A' }}"
                                disabled
                                class="input-field bg-gray-50"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <input
                                type="text"
                                value="{{ ucfirst(auth()->user()->role) }}"
                                disabled
                                class="input-field bg-gray-50"
                            >
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Member Since</p>
                    <p class="text-xl font-semibold text-gray-900">
                        {{ auth()->user()->created_at->format('M Y') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-primary text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                    <p class="text-xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Account Status</p>
                    <p class="text-xl font-semibold text-green-600">Active</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Section -->
    <div class="card mt-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-shield-alt text-primary mr-2"></i>
                Security
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium text-gray-900">Password</h4>
                    <p class="text-sm text-gray-600">Last changed: Never</p>
                </div>
                <button type="button" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fas fa-key mr-2"></i>
                    Change Password
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
