@extends('layouts.app')

@section('page-title', 'My Leave Requests')
@section('page-subtitle', 'View and track your leave applications')

@section('content')
<!-- Filters -->
<div class="card mb-6">
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Filter:</span>
                <select id="statusFilter" class="input-field py-2 px-3 w-40">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
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

            <div class="ml-auto">
                <a href="{{ route('leave.create') }}" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium inline-block">
                    <i class="fas fa-plus mr-2"></i>
                    New Request
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="overflow-x-auto">
        @if(isset($requests) && count($requests) > 0)
            <table class="data-table" id="requestsTable">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Leave Type</th>
                        <th>Date Range</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Manager Note</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr data-status="{{ $request->status }}" data-type="{{ $request->type }}">
                            <td>
                                <span class="font-mono text-sm text-gray-600">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                        @if($request->type == 'annual') bg-blue-100
                                        @elseif($request->type == 'sick') bg-green-100
                                        @elseif($request->type == 'personal') bg-yellow-100
                                        @elseif($request->type == 'maternity') bg-pink-100
                                        @elseif($request->type == 'paternity') bg-purple-100
                                        @else bg-gray-100
                                        @endif">
                                        <i class="fas
                                            @if($request->type == 'annual') fa-umbrella-beach text-blue-600
                                            @elseif($request->type == 'sick') fa-medkit text-green-600
                                            @elseif($request->type == 'personal') fa-user text-yellow-600
                                            @elseif($request->type == 'maternity') fa-baby text-pink-600
                                            @elseif($request->type == 'paternity') fa-baby-carriage text-purple-600
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
                                <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $request->reason }}">
                                    {{ $request->reason }}
                                </p>
                            </td>
                            <td>
                                <span class="status-badge
                                    @if($request->status == 'pending') status-pending
                                    @elseif($request->status == 'approved') status-approved
                                    @else status-rejected
                                    @endif">
                                    @if($request->status == 'pending')
                                        <i class="fas fa-clock mr-1"></i>
                                    @elseif($request->status == 'approved')
                                        <i class="fas fa-check mr-1"></i>
                                    @else
                                        <i class="fas fa-times mr-1"></i>
                                    @endif
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                @if($request->manager_note)
                                    <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $request->manager_note }}">
                                        {{ $request->manager_note }}
                                    </p>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button
                                        onclick="showRequestDetails({{ $request->id }})"
                                        class="p-2 text-gray-400 hover:text-primary transition"
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($request->status == 'pending')
                                        <button
                                            onclick="cancelRequest({{ $request->id }})"
                                            class="p-2 text-gray-400 hover:text-red-600 transition"
                                            title="Cancel Request">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
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
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Requests Found</h4>
                <p class="text-gray-600 mb-4">You haven't submitted any leave requests yet</p>
                <a href="{{ route('leave.create') }}" class="btn-primary px-6 py-2 rounded-lg text-sm font-medium inline-block">
                    <i class="fas fa-plus mr-2"></i>
                    Submit Your First Request
                </a>
            </div>
        @endif
    </div>

    @if(false)
        <div class="p-4 border-t border-gray-200">
                    <p>Pagination would go here</p>
        </div>
    @endif
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Request Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6" id="modalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const tableRows = document.querySelectorAll('#requestsTable tbody tr');

    function filterTable() {
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        tableRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowType = row.getAttribute('data-type');

            const statusMatch = !statusValue || rowStatus === statusValue;
            const typeMatch = !typeValue || rowType === typeValue;

            row.style.display = (statusMatch && typeMatch) ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', filterTable);
    typeFilter.addEventListener('change', filterTable);

    // Modal functions
    function showRequestDetails(id) {
        // In real implementation, fetch details via AJAX
        const modal = document.getElementById('detailsModal');
        const content = document.getElementById('modalContent');

        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                <p class="mt-4 text-gray-600">Loading details...</p>
            </div>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Fetch details
        fetch(`/leave/${id}`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Request ID</span>
                            <span class="font-mono font-medium">#${data.id}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Type</span>
                            <span class="font-medium">${data.type}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Duration</span>
                            <span class="font-medium">${data.start_date} to ${data.end_date}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Days</span>
                            <span class="font-medium">${data.total_days}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-600 mb-2">Reason</p>
                            <p class="text-gray-900">${data.reason}</p>
                        </div>
                        ${data.manager_note ? `
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-600 mb-2">Manager Note</p>
                            <p class="text-gray-900">${data.manager_note}</p>
                        </div>
                        ` : ''}
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-600 mb-2">Status</p>
                            <span class="status-badge status-${data.status}">${data.status}</span>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="fas fa-exclamation-circle text-3xl mb-4"></i>
                        <p>Failed to load details</p>
                    </div>
                `;
            });
    }

    function closeModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function cancelRequest(id) {
        if(confirm('Are you sure you want to cancel this request?')) {
            // In real implementation, send delete request
            fetch(`/leave/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Failed to cancel request');
                }
            })
            .catch(error => {
                alert('An error occurred');
            });
        }
    }

    // Close modal on outside click
    document.getElementById('detailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
@endsection
