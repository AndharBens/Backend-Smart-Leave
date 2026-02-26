<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Show employee dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get statistics from database
        $stats = [
            'total' => $user->leaveRequests()->count(),
            'pending' => $user->leaveRequests()->where('status', 'pending')->count(),
            'approved' => $user->leaveRequests()->where('status', 'approved')->count(),
            'rejected' => $user->leaveRequests()->where('status', 'rejected')->count(),
        ];

        $recentRequests = $user->leaveRequests()->latest()->take(5)->get();

        return view('employee.dashboard', compact('stats', 'recentRequests'));
    }

    /**
     * Show create leave request form
     */
    public function createLeave()
    {
        return view('employee.create');
    }

    /**
     * Store new leave request
     */
    public function storeLeave(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,personal,maternity,paternity,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Store leave request to database
        Auth::user()->leaveRequests()->create([
            'type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('leave.my-requests')
            ->with('success', 'Leave request submitted successfully!');
    }

    /**
     * Show employee's leave requests
     */
    public function myRequests()
    {
        $requests = Auth::user()->leaveRequests()->latest()->get();
        return view('employee.my-requests', compact('requests'));
    }

    /**
     * Show single leave request details
     */
    public function showLeave($id)
    {
        try {
            $request = Auth::user()->leaveRequests()->with(['user', 'approver'])->findOrFail($id);

            return response()->json([
                'id' => $request->id,
                'type' => $request->type ?? 'N/A',
                'start_date' => $request->start_date ? $request->start_date->format('M d, Y') : 'N/A',
                'end_date' => $request->end_date ? $request->end_date->format('M d, Y') : 'N/A',
                'total_days' => $request->total_days ?? 0,
                'reason' => $request->reason ?? 'No reason provided',
                'status' => $request->status ?? 'pending',
                'manager_note' => $request->manager_note,
                'created_at' => $request->created_at ? $request->created_at->format('M d, Y H:i') : 'N/A',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Leave request not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Cancel leave request
     */
    public function cancelLeave($id)
    {
        // Add cancellation logic here
        return response()->json([
            'success' => true,
            'message' => 'Leave request cancelled successfully'
        ]);
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('employee.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
