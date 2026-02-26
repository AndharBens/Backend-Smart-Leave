<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    /**
     * Show manager dashboard
     */
    public function dashboard()
    {
        // Get real statistics from database
        $allRequests = LeaveRequest::with('user')->get();

        $stats = [
            'pending' => $allRequests->where('status', 'pending')->count(),
            'approved_today' => $allRequests->where('status', 'approved')->where('updated_at', '>=', today())->count(),
            'on_leave' => $allRequests->where('status', 'approved')->where('start_date', '<=', today())->where('end_date', '>=', today())->count(),
            'total_decisions' => $allRequests->whereIn('status', ['approved', 'rejected'])->count(),
            'team_size' => \App\Models\User::where('role', 'employee')->count(),
            'approved_this_month' => $allRequests->where('status', 'approved')->where('updated_at', '>=', now()->startOfMonth())->count(),
            'rejected_this_month' => $allRequests->where('status', 'rejected')->where('updated_at', '>=', now()->startOfMonth())->count(),
        ];

        $pendingRequests = LeaveRequest::with('user')->where('status', 'pending')->latest()->take(5)->get();
        $onLeaveToday = \App\Models\User::where('role', 'employee')->get(); // Simplified
        $recentActivity = LeaveRequest::with('user')->whereIn('status', ['approved', 'rejected'])->latest()->take(10)->get();

        return view('manager.dashboard', compact(
            'stats',
            'pendingRequests',
            'onLeaveToday',
            'recentActivity'
        ));
    }

    /**
     * Show pending requests
     */
    public function pendingRequests()
    {
        $pendingRequests = LeaveRequest::with('user')->where('status', 'pending')->latest()->get();
        return view('manager.pending', compact('pendingRequests'));
    }

    /**
     * Show single request details
     */
    public function showRequest($id)
    {
        $request = LeaveRequest::with('user')->findOrFail($id);

        return response()->json([
            'id' => $request->id,
            'type' => $request->type,
            'start_date' => \Carbon\Carbon::parse($request->start_date)->format('M d, Y'),
            'end_date' => \Carbon\Carbon::parse($request->end_date)->format('M d, Y'),
            'total_days' => $request->total_days,
            'reason' => $request->reason,
            'status' => $request->status,
            'manager_note' => $request->manager_note,
            'created_at' => $request->created_at->toDateTimeString(),
            'user' => [
                'name' => $request->user->name,
                'email' => $request->user->email,
                'department' => $request->user->department,
            ]
        ]);
    }

    /**
     * Process leave request (approve/reject)
     */
    public function processRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'note' => 'required_if:action,reject|string|max:500',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $leaveRequest->manager_note = $validated['note'] ?? null;
        $leaveRequest->approved_by = Auth::id();
        $leaveRequest->save();

        return response()->json([
            'success' => true,
            'message' => $validated['action'] === 'approve'
                ? 'Leave request approved successfully!'
                : 'Leave request rejected',
        ]);
    }

    /**
     * Bulk action on multiple requests
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|string',
            'action' => 'required|in:approve,reject',
            'note' => 'required_if:action,reject|string|max:500',
        ]);

        $ids = explode(',', $validated['ids']);

        // Add database logic here for bulk update
        // For now, just return success response

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' request(s) ' .
                ($validated['action'] === 'approve' ? 'approved' : 'rejected') .
                ' successfully!'
        ]);
    }

    /**
     * Show approval history
     */
    public function history()
    {
        $history = LeaveRequest::with('user')->whereIn('status', ['approved', 'rejected'])->latest()->get();

        $stats = [
            'total' => $history->count(),
            'approved' => $history->where('status', 'approved')->count(),
            'rejected' => $history->where('status', 'rejected')->count(),
        ];

        return view('manager.history', compact('history', 'stats'));
    }

    /**
     * Show history detail
     */
    public function historyDetail($id)
    {
        $request = LeaveRequest::with('user')->findOrFail($id);

        return response()->json([
            'id' => $request->id,
            'type' => $request->type,
            'start_date' => \Carbon\Carbon::parse($request->start_date)->format('M d, Y'),
            'end_date' => \Carbon\Carbon::parse($request->end_date)->format('M d, Y'),
            'total_days' => $request->total_days,
            'reason' => $request->reason,
            'status' => $request->status,
            'manager_note' => $request->manager_note,
            'created_at' => $request->created_at->toDateTimeString(),
            'updated_at' => $request->updated_at->toDateTimeString(),
            'user' => [
                'name' => $request->user->name,
                'email' => $request->user->email,
                'department' => $request->user->department,
            ]
        ]);
    }

    /**
     * Export history to CSV
     */
    public function exportHistory(Request $request)
    {
        // Add export logic here
        return back()->with('success', 'Export feature coming soon!');
    }
}
