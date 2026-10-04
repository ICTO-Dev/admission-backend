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
        Schema::create('applicant_health_emergency', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();

            // Health Conditions
            $table->boolean('pwd_status')->default(false);
            $table->string('pwd_specs')->nullable();
            $table->boolean('hospitalized_status')->default(false);
            $table->text('hospitalized_reasons')->nullable();

            // Emergency Contact Details
            $table->string('emergency_name');
            $table->string('emergency_relation');
            $table->string('emergency_contact', 20);
            $table->text('emergency_address');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_health_emergency');
    }
};
