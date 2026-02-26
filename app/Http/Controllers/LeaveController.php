<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('role:employee,manager,admin')
            ->only('create');

        $this->middleware('role:manager,admin')
            ->only(['pending', 'approve', 'reject']);
    }

    /**
     * Employee create leave request
     */
    public function create(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string',
        ]);

        // ❌ NO ID HERE
        $leave = LeaveRequest::create([
            'user_id'    => Auth::id(),
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return response()->json($leave, 201);
    }

    /**
     * Employee see own requests
     */
    public function myRequests()
    {
        $requests = LeaveRequest::where('user_id', Auth::id())->get();

        return response()->json($requests);
    }

    /**
     * Manager/Admin see pending requests
     */
    public function pending()
    {
        $requests = LeaveRequest::where('status', 'pending')->get();

        return response()->json($requests);
    }

    /**
     * Approve leave
     */
    public function approve($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if ($leave->status !== 'pending') {
            return response()->json([
                'error' => 'Already processed'
            ], 400);
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return response()->json($leave);
    }

    /**
     * Reject leave
     */
    public function reject($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if ($leave->status !== 'pending') {
            return response()->json([
                'error' => 'Already processed'
            ], 400);
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return response()->json($leave);
    }
}