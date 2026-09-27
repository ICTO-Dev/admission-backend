<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->index(['campus_id', 'school_year_id', 'status'], 'idx_exam_sched_campus_sy_status');
            $table->index(['campus_id', 'exam_date', 'start_time'], 'idx_exam_sched_campus_date_time');
            $table->index(['batch_id', 'status'], 'idx_exam_sched_batch_status');
            $table->index(['room_id', 'exam_date'], 'idx_exam_sched_room_date');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('status', 'idx_apps_status');
            $table->index(['campus_id', 'status'], 'idx_apps_campus_status');
            $table->index('last_name', 'idx_apps_last_name');
            $table->index('first_name', 'idx_apps_first_name');
            $table->index('email_address', 'idx_apps_email');
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->index(['campus_id', 'school_year_id', 'status'], 'idx_batches_campus_sy_status');
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->index(['campus_id', 'venue_name'], 'idx_venues_campus_name');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['venue_id', 'status'], 'idx_rooms_venue_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropIndex('idx_exam_sched_campus_sy_status');
            $table->dropIndex('idx_exam_sched_campus_date_time');
            $table->dropIndex('idx_exam_sched_batch_status');
            $table->dropIndex('idx_exam_sched_room_date');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_apps_status');
            $table->dropIndex('idx_apps_campus_status');
            $table->dropIndex('idx_apps_last_name');
            $table->dropIndex('idx_apps_first_name');
            $table->dropIndex('idx_apps_email');
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropIndex('idx_batches_campus_sy_status');
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->dropIndex('idx_venues_campus_name');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex('idx_rooms_venue_status');
        });
    }
};
