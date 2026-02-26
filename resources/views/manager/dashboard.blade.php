@extends('layouts.app')

@section('page-title', 'Manager Dashboard')
@section('page-subtitle', 'Review and manage team leave requests')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Pending Review</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
        </div>
        @if($stats['pending'] > 0)
        <div class="mt-4">
            <a href="{{ route('manager.pending') }}" class="text-sm font-medium text-primary hover:text-primary-dark">
                Review now <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Approved Today</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['approved_today'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-double text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">On Leave Today</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['on_leave'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-clock text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Decisions</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_decisions'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-tasks text-gray-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Pending Requests -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Pending Requests -->
    <div class="lg:col-span-2 card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Pending Requests</h3>
                    <p class="text-sm text-gray-600">Requests awaiting your approval</p>
                </div>
                @if(isset($pendingRequests) && count($pendingRequests) > 0)
                <a href="{{ route('manager.pending') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                    View All
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endif
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @if(isset($pendingRequests) && count($pendingRequests) > 0)
                @foreach($pendingRequests->take(5) as $request)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($request->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $request->user->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $request->user->department ?? 'N/A' }}</p>
                                <div class="mt-2 flex items-center space-x-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($request->type == 'annual') bg-blue-100 text-blue-700
                                        @elseif($request->type == 'sick') bg-green-100 text-green-700
                                        @elseif($request->type == 'personal') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst($request->type) }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $request->total_days ?? '-' }} days
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $request->reason }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button
                                onclick="quickApprove({{ $request->id }})"
                                class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition"
                                title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button
                                onclick="quickReject({{ $request->id }})"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                            <button
                                onclick="viewRequest({{ $request->id }})"
                                class="p-2 text-gray-400 hover:text-primary hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">All Caught Up!</h4>
                    <p class="text-gray-600">No pending requests to review</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Team on Leave Today -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user-clock text-primary mr-2"></i>
                On Leave Today
            </h3>
            <p class="text-sm text-gray-600 mt-1">{{ date('F j, Y') }}</p>
        </div>

        <div class="p-6">
            @if(isset($onLeaveToday) && count($onLeaveToday) > 0)
                <div class="space-y-4">
                    @foreach($onLeaveToday as $employee)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $employee->name }}</p>
                            <p class="text-xs text-gray-600">{{ $employee->department ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-600 text-sm">No team members on leave today</p>
                </div>
            @endif
        </div>

        <div class="p-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Stats</h4>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Team Size</span>
                    <span class="font-medium">{{ $stats['team_size'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Approved This Month</span>
                    <span class="font-medium text-green-600">{{ $stats['approved_this_month'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Rejected This Month</span>
                    <span class="font-medium text-red-600">{{ $stats['rejected_this_month'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <p class="text-sm text-gray-600">Your latest approval decisions</p>
            </div>
            <a href="{{ route('manager.history') }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                View History
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        @if(isset($recentActivity) && count($recentActivity) > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Date Range</th>
                        <th>Days</th>
                        <th>Your Decision</th>
                        <th>Action Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity->take(10) as $activity)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                    {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $activity->user->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $activity->user->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($activity->type == 'annual') bg-blue-100 text-blue-700
                                @elseif($activity->type == 'sick') bg-green-100 text-green-700
                                @elseif($activity->type == 'personal') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </td>
                        <td>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}</p>
                        </td>
                        <td>{{ $activity->total_days ?? '-' }}</td>
                        <td>
                            <span class="status-badge
                                @if($activity->status == 'approved') status-approved
                                @else status-rejected
                                @endif">
                                @if($activity->status == 'approved')
                                    <i class="fas fa-check mr-1"></i>
                                @else
                                    <i class="fas fa-times mr-1"></i>
                                @endif
                                {{ ucfirst($activity->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Recent Activity</h4>
                <p class="text-gray-600">Start reviewing pending requests</p>
            </div>
        @endif
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Review Leave Request</h3>
                <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6" id="approvalModalContent">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentRequestId = null;

    function quickApprove(id) {
        if(confirm('Approve this leave request?')) {
            submitDecision(id, 'approve');
        }
    }

    function quickReject(id) {
        const note = prompt('Please provide a reason for rejection:');
        if(note !== null) {
            submitDecision(id, 'reject', note);
        }
    }

    function viewRequest(id) {
        currentRequestId = id;
        const modal = document.getElementById('approvalModal');
        const content = document.getElementById('approvalModalContent');

        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                <p class="mt-4 text-gray-600">Loading request details...</p>
            </div>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/manager/leave/${id}`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
                    <form id="approvalForm">
                        <input type="hidden" name="request_id" value="${data.id}">
                        <input type="hidden" name="action" id="actionInput">

                        <div class="space-y-6">
                            <!-- Employee Info -->
                            <div class="flex items-center space-x-4 pb-6 border-b">
                                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-2xl">
                                    ${data.user.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900">${data.user.name}</h4>
                                    <p class="text-gray-600">${data.user.department || 'N/A'}</p>
                                    <p class="text-sm text-gray-500">${data.user.email}</p>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Leave Type</label>
                                    <p class="mt-1 text-gray-900">${data.type}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Total Days</label>
                                    <p class="mt-1 text-gray-900">${data.total_days}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Start Date</label>
                                    <p class="mt-1 text-gray-900">${data.start_date}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">End Date</label>
                                    <p class="mt-1 text-gray-900">${data.end_date}</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Reason</label>
                                <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">${data.reason}</p>
                            </div>

                            <!-- Manager Note -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Manager Note (Optional)</label>
                                <textarea id="managerNote" name="note" rows="3" class="input-field mt-1" placeholder="Add a note for the employee..."></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 pt-6 border-t flex justify-end space-x-3">
                            <button type="button" onclick="closeApprovalModal()" class="btn-secondary px-6 py-2.5 rounded-lg font-medium">
                                Cancel
                            </button>
                            <button type="button" onclick="submitFormWithAction('reject')" class="px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition">
                                <i class="fas fa-times mr-2"></i>
                                Reject
                            </button>
                            <button type="button" onclick="submitFormWithAction('approve')" class="btn-primary px-6 py-2.5 rounded-lg font-medium">
                                <i class="fas fa-check mr-2"></i>
                                Approve
                            </button>
                        </div>
                    </form>
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="fas fa-exclamation-circle text-3xl mb-4"></i>
                        <p>Failed to load request details</p>
                    </div>
                `;
            });
    }

    function submitFormWithAction(action) {
        if(action === 'reject') {
            const note = document.getElementById('managerNote').value;
            if(!note) {
                alert('Please provide a reason for rejection');
                document.getElementById('managerNote').focus();
                return;
            }
        }

        const note = document.getElementById('managerNote').value;
        submitDecision(currentRequestId, action, note);
    }

    function submitDecision(id, action, note = '') {
        const formData = new FormData();
        formData.append('_method', 'PATCH');
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('action', action);
        if(note) formData.append('note', note);

        fetch(`/manager/leave/${id}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                closeApprovalModal();
                location.reload();
            } else {
                alert(data.message || 'Action failed');
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }

    function closeApprovalModal() {
        const modal = document.getElementById('approvalModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentRequestId = null;
    }

    // Close modal on outside click
    document.getElementById('approvalModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeApprovalModal();
        }
    });
</script>
@endpush
@endsection
