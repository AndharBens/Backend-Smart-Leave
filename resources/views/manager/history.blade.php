@extends('layouts.app')

@section('page-title', 'Approval History')
@section('page-subtitle', 'View your past approval decisions')

@section('content')
<!-- Filters -->
<div class="card mb-6">
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <select id="statusFilter" class="input-field py-2 px-3 w-40">
                    <option value="">All Status</option>
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

            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Period:</span>
                <select id="periodFilter" class="input-field py-2 px-3 w-48">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>

            <div class="ml-auto flex items-center space-x-2">
                <button onclick="exportHistory()" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Decisions</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tasks text-gray-600"></i>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Approved</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Rejected</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Approval Rate</p>
                <p class="text-2xl font-bold text-primary">
                    @if($stats['total'] ?? 0 > 0)
                        {{ round((($stats['approved'] ?? 0) / ($stats['total'] ?? 1)) * 100) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-percentage text-primary"></i>
            </div>
        </div>
    </div>
</div>

<!-- History Table -->
<div class="card">
    <div class="overflow-x-auto">
        @if(isset($history) && count($history) > 0)
            <table class="data-table" id="historyTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Date Range</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Your Decision</th>
                        <th>Your Note</th>
                        <th>Decision Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $record)
                        <tr data-status="{{ $record->status }}" data-type="{{ $record->type }}" data-date="{{ $record->updated_at }}">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($record->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $record->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $record->user->department ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($record->type == 'annual') bg-blue-100 text-blue-700
                                    @elseif($record->type == 'sick') bg-green-100 text-green-700
                                    @elseif($record->type == 'personal') bg-yellow-100 text-yellow-700
                                    @elseif($record->type == 'maternity') bg-pink-100 text-pink-700
                                    @elseif($record->type == 'paternity') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($record->type) }}
                                </span>
                            </td>
                            <td>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->start_date)->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($record->end_date)->format('M d, Y') }}</p>
                            </td>
                            <td>{{ $record->total_days ?? '-' }}</td>
                            <td>
                                <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $record->reason }}">
                                    {{ $record->reason }}
                                </p>
                            </td>
                            <td>
                                <span class="status-badge
                                    @if($record->status == 'approved') status-approved
                                    @else status-rejected
                                    @endif">
                                    @if($record->status == 'approved')
                                        <i class="fas fa-check mr-1"></i>
                                    @else
                                        <i class="fas fa-times mr-1"></i>
                                    @endif
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td>
                                @if($record->manager_note)
                                    <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $record->manager_note }}">
                                        {{ $record->manager_note }}
                                    </p>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($record->updated_at)->format('M d, Y H:i') }}</span>
                            </td>
                            <td>
                                <button
                                    onclick="viewDetails({{ $record->id }})"
                                    class="p-2 text-gray-400 hover:text-primary transition"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
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
                <h4 class="text-lg font-medium text-gray-900 mb-2">No History Yet</h4>
                <p class="text-gray-600 mb-4">Start reviewing pending requests to build your history</p>
                <a href="{{ route('manager.pending') }}" class="btn-primary px-6 py-2 rounded-lg text-sm font-medium inline-block">
                    <i class="fas fa-clock mr-2"></i>
                    Review Pending Requests
                </a>
            </div>
        @endif
    </div>

    @if(isset($history) && false)
        <div class="p-4 border-t border-gray-200">
            Pagination would go here
        </div>
    @endif
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Decision Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6" id="modalContent">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterTable() {
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        const period = document.getElementById('periodFilter').value;

        const rows = document.querySelectorAll('#historyTable tbody tr');
        const now = new Date();

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowType = row.getAttribute('data-type');
            const rowDate = new Date(row.getAttribute('data-date'));

            const statusMatch = !status || rowStatus === status;
            const typeMatch = !type || rowType === type;

            let periodMatch = true;
            if(period !== 'all') {
                const diffTime = now - rowDate;
                const diffDays = diffTime / (1000 * 60 * 60 * 24);

                switch(period) {
                    case 'today':
                        periodMatch = diffDays < 1;
                        break;
                    case 'week':
                        periodMatch = diffDays < 7;
                        break;
                    case 'month':
                        periodMatch = diffDays < 30;
                        break;
                    case 'quarter':
                        periodMatch = diffDays < 90;
                        break;
                    case 'year':
                        periodMatch = diffDays < 365;
                        break;
                }
            }

            row.style.display = (statusMatch && typeMatch && periodMatch) ? '' : 'none';
        });
    }

    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('typeFilter').addEventListener('change', filterTable);
    document.getElementById('periodFilter').addEventListener('change', filterTable);

    function viewDetails(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('modalContent');

        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-primary text-3xl"></i>
                <p class="mt-4 text-gray-600">Loading details...</p>
            </div>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/manager/history/${id}`)
            .then(response => response.json())
            .then(data => {
                const statusBadge = data.status === 'approved'
                    ? '<span class="status-badge status-approved"><i class="fas fa-check mr-1"></i>Approved</span>'
                    : '<span class="status-badge status-rejected"><i class="fas fa-times mr-1"></i>Rejected</span>';

                content.innerHTML = `
                    <div class="space-y-6">
                        <!-- Status Banner -->
                        <div class="p-4 rounded-lg ${data.status === 'approved' ? 'bg-green-50' : 'bg-red-50'}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium ${data.status === 'approved' ? 'text-green-800' : 'text-red-800'}">
                                        Your Decision
                                    </p>
                                    ${statusBadge}
                                </div>
                                <p class="text-sm ${data.status === 'approved' ? 'text-green-600' : 'text-red-600'}">
                                    ${new Date(data.updated_at).toLocaleString()}
                                </p>
                            </div>
                        </div>

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
                            <div>
                                <label class="text-sm font-medium text-gray-700">Submitted</label>
                                <p class="mt-1 text-gray-900">${new Date(data.created_at).toLocaleString()}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Decision Made</label>
                                <p class="mt-1 text-gray-900">${new Date(data.updated_at).toLocaleString()}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Employee Reason</label>
                            <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">${data.reason}</p>
                        </div>

                        ${data.manager_note ? `
                        <div>
                            <label class="text-sm font-medium text-gray-700">Your Note</label>
                            <p class="mt-1 text-gray-900 bg-blue-50 p-3 rounded-lg border border-blue-200">${data.manager_note}</p>
                        </div>
                        ` : ''}

                        <div class="pt-6 border-t">
                            <button onclick="closeModal()" class="btn-secondary w-full px-6 py-2.5 rounded-lg font-medium">
                                Close
                            </button>
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
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }

    function exportHistory() {
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        const period = document.getElementById('periodFilter').value;

        const params = new URLSearchParams();
        if(status) params.append('status', status);
        if(type) params.append('type', type);
        if(period) params.append('period', period);

        window.location.href = `/manager/history/export?${params.toString()}`;
    }

    // Close modal on outside click
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush
@endsection
