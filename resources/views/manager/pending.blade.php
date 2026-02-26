@extends('layouts.app')

@section('page-title', 'Pending Requests')
@section('page-subtitle', 'Review and approve team leave requests')

@section('content')
<!-- Filters -->
<div class="card mb-6">
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Department:</span>
                <select id="departmentFilter" class="input-field py-2 px-3 w-48">
                    <option value="">All Departments</option>
                    <option value="engineering">Engineering</option>
                    <option value="marketing">Marketing</option>
                    <option value="finance">Finance</option>
                    <option value="hr">Human Resources</option>
                    <option value="operations">Operations</option>
                    <option value="sales">Sales</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Type:</span>
                <select id="typeFilter" class="input-field py-2 px-3 w-40">
                    <option value="">All Types</option>
                    <option value="annual">Annual</option>
                    <option value="sick">Sick</option>
                    <option value="personal">Personal</option>
                    <option value="maternity">Maternity</option>
                    <option value="paternity">Paternity</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Sort:</span>
                <select id="sortFilter" class="input-field py-2 px-3 w-48">
                    <option value="date_desc">Newest First</option>
                    <option value="date_asc">Oldest First</option>
                    <option value="duration_desc">Longest Duration</option>
                    <option value="duration_asc">Shortest Duration</option>
                </select>
            </div>

            <div class="ml-auto flex items-center space-x-2">
                <span class="text-sm text-gray-600">
                    <strong class="text-primary">{{ count($pendingRequests) }}</strong> pending requests
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Pending Requests Table -->
<div class="card">
    @if(isset($pendingRequests) && count($pendingRequests) > 0)
        <div class="overflow-x-auto">
            <table class="data-table" id="requestsTable">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        </th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Date Range</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $request)
                        <tr data-department="{{ $request->user->department ?? '' }}" data-type="{{ $request->type }}" data-date="{{ $request->created_at }}" data-duration="{{ $request->total_days ?? 0 }}">
                            <td>
                                <input type="checkbox" class="request-checkbox w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" data-id="{{ $request->id }}">
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $request->user->department ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($request->type == 'annual') bg-blue-100 text-blue-700
                                    @elseif($request->type == 'sick') bg-green-100 text-green-700
                                    @elseif($request->type == 'personal') bg-yellow-100 text-yellow-700
                                    @elseif($request->type == 'maternity') bg-pink-100 text-pink-700
                                    @elseif($request->type == 'paternity') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($request->type) }}
                                </span>
                            </td>
                            <td>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500">to {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</p>
                            </td>
                            <td>
                                <span class="font-medium">{{ $request->total_days ?? '-' }}</span>
                            </td>
                            <td>
                                <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $request->reason }}">
                                    {{ $request->reason }}
                                </p>
                            </td>
                            <td>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button
                                        onclick="approveRequest({{ $request->id }})"
                                        class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition"
                                        title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button
                                        onclick="rejectRequest({{ $request->id }})"
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        @if(count($pendingRequests) > 0)
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        <span id="selectedCount">0</span> selected
                    </span>
                    <button
                        id="bulkApprove"
                        onclick="bulkApprove()"
                        class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium hidden">
                        <i class="fas fa-check mr-2"></i>
                        Approve Selected
                    </button>
                    <button
                        id="bulkReject"
                        onclick="bulkReject()"
                        class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium hidden">
                        <i class="fas fa-times mr-2"></i>
                        Reject Selected
                    </button>
                </div>

                @if(false)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Showing {{ $pendingRequests->firstItem() }} to {{ $pendingRequests->lastItem() }} of {{ $pendingRequests->total() }}</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if(false)
            <div class="p-4 border-t border-gray-200">
                <p>Pagination would go here</p>
            </div>
        @endif
    @else
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-500 text-4xl"></i>
            </div>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">All Caught Up!</h4>
            <p class="text-gray-600 mb-4">No pending requests to review</p>
            <a href="{{ route('manager.dashboard') }}" class="btn-primary px-6 py-2.5 rounded-lg text-sm font-medium inline-block">
                <i class="fas fa-chart-line mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    @endif
</div>

<!-- Request Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Request Details</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6" id="detailModalContent">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

<!-- Reject Note Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                Reject Request
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">Please provide a reason for rejecting this request:</p>
            <textarea id="rejectReason" class="input-field resize-none" rows="4" placeholder="Enter reason for rejection..."></textarea>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
            <button onclick="closeRejectModal()" class="btn-secondary px-6 py-2.5 rounded-lg font-medium">
                Cancel
            </button>
            <button onclick="confirmReject()" class="px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition">
                <i class="fas fa-times mr-2"></i>
                Reject
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentRequestId = null;
    let selectedIds = [];

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    document.querySelectorAll('.request-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        selectedIds = Array.from(checked).map(cb => cb.dataset.id);
        document.getElementById('selectedCount').textContent = selectedIds.length;

        const bulkApprove = document.getElementById('bulkApprove');
        const bulkReject = document.getElementById('bulkReject');

        if(selectedIds.length > 0) {
            bulkApprove.classList.remove('hidden');
            bulkReject.classList.remove('hidden');
        } else {
            bulkApprove.classList.add('hidden');
            bulkReject.classList.add('hidden');
        }
    }

    function approveRequest(id) {
        if(confirm('Approve this leave request?')) {
            submitDecision(id, 'approve');
        }
    }

    function rejectRequest(id) {
        currentRequestId = id;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
        document.getElementById('rejectReason').focus();
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
        document.getElementById('rejectReason').value = '';
        currentRequestId = null;
    }

    function confirmReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        if(!reason) {
            alert('Please provide a reason for rejection');
            return;
        }
        closeRejectModal();
        submitDecision(currentRequestId, 'reject', reason);
    }

    function bulkApprove() {
        if(selectedIds.length === 0) return;
        if(confirm(`Approve ${selectedIds.length} request(s)?`)) {
            bulkAction('approve');
        }
    }

    function bulkReject() {
        if(selectedIds.length === 0) return;
        const reason = prompt('Please provide a reason for rejecting these requests:');
        if(reason !== null) {
            bulkAction('reject', reason);
        }
    }

    function bulkAction(action, note = '') {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('action', action);
        formData.append('ids', selectedIds.join(','));
        if(note) formData.append('note', note);

        fetch('/manager/leave/bulk', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'Bulk action failed');
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }

    function viewRequest(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailModalContent');

        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                <p class="mt-4 text-gray-600">Loading details...</p>
            </div>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/manager/leave/${id}`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
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
                            <div class="col-span-2">
                                <label class="text-sm font-medium text-gray-700">Submitted</label>
                                <p class="mt-1 text-gray-900">${new Date(data.created_at).toLocaleString()}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Reason</label>
                            <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">${data.reason}</p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="pt-6 border-t flex justify-end space-x-3">
                            <button onclick="closeDetailModal()" class="btn-secondary px-6 py-2.5 rounded-lg font-medium">
                                Close
                            </button>
                            <button onclick="closeDetailModal(); rejectRequest(${data.id})" class="px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition">
                                <i class="fas fa-times mr-2"></i>
                                Reject
                            </button>
                            <button onclick="closeDetailModal(); approveRequest(${data.id})" class="btn-primary px-6 py-2.5 rounded-lg font-medium">
                                <i class="fas fa-check mr-2"></i>
                                Approve
                            </button>
                        </div>
                    </div>
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

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
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
                location.reload();
            } else {
                alert(data.message || 'Action failed');
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }

    // Close modals on outside click
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });

    // Filter functionality (basic implementation)
    document.getElementById('departmentFilter').addEventListener('change', filterTable);
    document.getElementById('typeFilter').addEventListener('change', filterTable);

    function filterTable() {
        const dept = document.getElementById('departmentFilter').value;
        const type = document.getElementById('typeFilter').value;
        const rows = document.querySelectorAll('#requestsTable tbody tr');

        rows.forEach(row => {
            const rowDept = row.getAttribute('data-department');
            const rowType = row.getAttribute('data-type');

            const deptMatch = !dept || rowDept === dept;
            const typeMatch = !type || rowType === type;

            row.style.display = (deptMatch && typeMatch) ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
