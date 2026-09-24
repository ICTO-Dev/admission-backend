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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no')->unique()->index();
            $table->string('school_year')->default('2026-2027');
            $table->string('student_type')->default('Freshman'); // Freshman, Transferee, Second Courser
            
            // Program, Campus, and Location Foreign Keys
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete();
            $table->foreignId('course_1_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('course_2_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('barangay_id', 15)->nullable()->index();

            // Personal Information
            $table->string('lrn', 12)->index();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->unsignedTinyInteger('age');
            $table->string('sex', 10);
            $table->string('civil_status', 30);
            $table->string('place_of_birth');
            $table->string('religion');
            $table->string('nationality');
            
            // Contact & Addresses
            $table->text('present_address');
            $table->text('permanent_address');
            $table->string('mobile_number', 20);
            $table->string('email_address');
            $table->string('photo_url')->nullable();

            // Demographics & Status
            $table->boolean('is_indigenous')->default(false);
            $table->string('indigenous_group')->nullable();
            $table->boolean('is_solo_parent')->default(false);
            $table->string('status')->default('Pending'); // Pending, Approved, Scheduled, Rejected
            $table->string('exam_schedule_slot_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
