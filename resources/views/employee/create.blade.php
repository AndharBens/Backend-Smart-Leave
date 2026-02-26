@extends('layouts.app')

@section('page-title', 'New Leave Request')
@section('page-subtitle', 'Submit a new leave application')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-900">New Leave Request</span>
    </div>

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-plus-circle text-primary mr-2"></i>
                Leave Request Details
            </h3>
            <p class="text-sm text-gray-600 mt-1">Fill in the information below to submit your leave request</p>
        </div>

        <form method="POST" action="{{ route('leave.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-tag"></i>
                            </span>
                            <select name="leave_type" required class="input-field pl-10">
                                <option value="">Select leave type</option>
                                <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>
                                    <i class="fas fa-umbrella-beach"></i> Annual Leave
                                </option>
                                <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>
                                    <i class="fas fa-medkit"></i> Sick Leave
                                </option>
                                <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>
                                    <i class="fas fa-user"></i> Personal Leave
                                </option>
                                <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>
                                    <i class="fas fa-baby"></i> Maternity Leave
                                </option>
                                <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>
                                    <i class="fas fa-baby-carriage"></i> Paternity Leave
                                </option>
                                <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>
                                    <i class="fas fa-hand-holding-usd"></i> Unpaid Leave
                                </option>
                            </select>
                        </div>
                        @error('leave_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input
                                type="date"
                                name="start_date"
                                required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('start_date') }}"
                                class="input-field pl-10"
                            >
                        </div>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <input
                                type="date"
                                name="end_date"
                                required
                                min="{{ old('start_date') ?? date('Y-m-d') }}"
                                value="{{ old('end_date') }}"
                                class="input-field pl-10"
                            >
                        </div>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Total Days
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-calculator"></i>
                            </span>
                            <input
                                type="text"
                                id="totalDays"
                                readonly
                                class="input-field pl-10 bg-gray-50"
                                placeholder="Calculated automatically"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated based on date range</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="reason"
                            required
                            rows="8"
                            class="input-field resize-none"
                            placeholder="Please provide a reason for your leave request..."
                        >{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Attachment (Optional)
                        </label>
                        <div class="relative">
                            <input
                                type="file"
                                name="attachment"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="input-field"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Leave Balance Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Your Leave Balance
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-blue-700">Annual Leave</span>
                                <span class="font-medium text-blue-900">12 days remaining</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Sick Leave</span>
                                <span class="font-medium text-blue-900">11 days remaining</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Personal Leave</span>
                                <span class="font-medium text-blue-900">0 days remaining</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" class="btn-secondary px-6 py-2.5 rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary px-8 py-2.5 rounded-lg font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Calculate total days
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const totalDaysInput = document.getElementById('totalDays');

    function calculateDays() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDateInput.value && endDateInput.value && endDate >= startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            totalDaysInput.value = diffDays + ' day' + (diffDays > 1 ? 's' : '');
        } else {
            totalDaysInput.value = '';
        }
    }

    startDateInput.addEventListener('change', calculateDays);
    endDateInput.addEventListener('change', calculateDays);
</script>
@endpush
@endsection
