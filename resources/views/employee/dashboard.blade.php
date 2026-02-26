@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Requests</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt text-primary text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Create New Request Card -->
    <div class="card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                <p class="text-sm text-gray-600">Manage your leave requests</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('leave.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">New Leave Request</p>
                    <p class="text-sm text-gray-600">Submit a new leave application</p>
                </div>
            </a>

            <a href="{{ route('leave.my-requests') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                    <i class="fas fa-list text-white"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">View All Requests</p>
                    <p class="text-sm text-gray-600">See your request history</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Leave Balance Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-balance-scale text-primary mr-2"></i>
            Leave Balance
        </h3>

        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Annual Leave</span>
                    <span class="font-medium">12 / 20 days</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: 60%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Sick Leave</span>
                    <span class="font-medium">3 / 14 days</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 21%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Personal Leave</span>
                    <span class="font-medium">5 / 5 days</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requests -->
<div class="card">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Requests</h3>
                <p class="text-sm text-gray-600">Your latest leave applications</p>
            </div>
            <a href="{{ route('leave.my-requests') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                View All
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        @if(isset($recentRequests) && count($recentRequests) > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Date Range</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests as $request)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                        @if($request->type == 'annual') bg-blue-100
                                        @elseif($request->type == 'sick') bg-green-100
                                        @elseif($request->type == 'personal') bg-yellow-100
                                        @else bg-gray-100
                                        @endif">
                                        <i class="fas
                                            @if($request->type == 'annual') fa-umbrella-beach text-blue-600
                                            @elseif($request->type == 'sick') fa-medkit text-green-600
                                            @elseif($request->type == 'personal') fa-user text-yellow-600
                                            @else fa-calendar text-gray-600
                                            @endif text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ ucfirst($request->type) }}</span>
                                </div>
                            </td>
                            <td>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500">to {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</p>
                            </td>
                            <td>
                                <span class="font-medium">{{ $request->total_days ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="status-badge
                                    @if($request->status == 'pending') status-pending
                                    @elseif($request->status == 'approved') status-approved
                                    @else status-rejected
                                    @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                        </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Requests Yet</h4>
                <p class="text-gray-600 mb-4">You haven't submitted any leave requests</p>
                <a href="{{ route('leave.create') }}" class="btn-primary px-6 py-2 rounded-lg text-sm font-medium inline-block">
                    <i class="fas fa-plus mr-2"></i>
                    Submit Your First Request
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
