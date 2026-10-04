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
        Schema::create('applicant_family_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();

            // Father's Profile
            $table->string('father_name')->nullable();
            $table->unsignedTinyInteger('father_age')->nullable();
            $table->string('father_birthplace')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_workplace')->nullable();
            $table->string('father_living_status')->nullable(); // Living, Deceased
            $table->string('father_cause_of_death')->nullable();
            $table->string('father_living_with_family')->nullable(); // Yes, No, Abroad, Separated

            // Mother's Profile (Maiden Name)
            $table->string('mother_name')->nullable();
            $table->unsignedTinyInteger('mother_age')->nullable();
            $table->string('mother_birthplace')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_workplace')->nullable();
            $table->string('mother_living_status')->nullable();
            $table->string('mother_cause_of_death')->nullable();
            $table->string('mother_living_with_family')->nullable();

            // Spouse's Profile (if married)
            $table->string('spouse_name')->nullable();
            $table->unsignedTinyInteger('spouse_age')->nullable();
            $table->string('spouse_birthplace')->nullable();
            $table->string('spouse_education')->nullable();
            $table->string('spouse_contact')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_workplace')->nullable();
            $table->string('spouse_living_status')->nullable();
            $table->string('spouse_living_with_family')->nullable();
            $table->unsignedSmallInteger('spouse_dependents')->nullable();

            // Socio-demographics
            $table->string('birth_order')->nullable();
            $table->string('birth_order_other')->nullable();
            $table->string('housing_condition')->nullable();
            $table->string('family_monthly_income')->nullable();
            $table->string('language_spoken')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_family_profiles');
    }
};
