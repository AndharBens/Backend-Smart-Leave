<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employee = User::where('email', 'employee1@test.com')->first();
        $manager  = User::where('email', 'manager@test.com')->first();

        if ($employee && $manager) {

            LeaveRequest::create([
                'user_id' => $employee->id,
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-03',
                'reason' => 'Sakit cuy',
                'status' => 'pending',
            ]);

            LeaveRequest::create([
                'user_id' => $employee->id,
                'start_date' => '2026-03-10',
                'end_date' => '2026-03-12',
                'reason' => 'Liburan keluarga',
                'status' => 'pending',
            ]);

            LeaveRequest::create([
                'user_id' => $employee->id,
                'start_date' => '2026-02-20',
                'end_date' => '2026-02-22',
                'reason' => 'Urgent matter',
                'status' => 'approved',
                'approved_by' => $manager->id,
            ]);
        }
    }
}