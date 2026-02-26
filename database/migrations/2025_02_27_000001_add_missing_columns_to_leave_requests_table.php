<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_requests', 'type')) {
                $table->string('type')->after('user_id');
            }
            if (!Schema::hasColumn('leave_requests', 'total_days')) {
                $table->integer('total_days')->after('end_date');
            }
            if (!Schema::hasColumn('leave_requests', 'manager_note')) {
                $table->text('manager_note')->nullable()->after('reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('leave_requests', 'total_days')) {
                $table->dropColumn('total_days');
            }
            if (Schema::hasColumn('leave_requests', 'manager_note')) {
                $table->dropColumn('manager_note');
            }
        });
    }
};
