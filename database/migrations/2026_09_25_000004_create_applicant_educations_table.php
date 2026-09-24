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
        Schema::create('applicant_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();

            // Elementary
            $table->string('elem_name')->nullable();
            $table->string('elem_grad_year', 10)->nullable();
            $table->string('elem_address')->nullable();
            $table->string('elem_awards')->nullable();

            // Junior High School
            $table->string('jhs_name')->nullable();
            $table->string('jhs_grad_year', 10)->nullable();
            $table->string('jhs_address')->nullable();
            $table->string('jhs_awards')->nullable();

            // Senior High School
            $table->string('shs_name')->nullable();
            $table->string('shs_grad_year', 10)->nullable();
            $table->string('shs_address')->nullable();
            $table->string('shs_track')->nullable();
            $table->string('shs_awards')->nullable();
            $table->string('shs_avg_g11', 20)->nullable();
            $table->string('shs_avg_g12', 20)->nullable();

            // College (Transferee / Second Courser)
            $table->string('coll_name')->nullable();
            $table->string('coll_years', 30)->nullable();
            $table->string('coll_address')->nullable();
            $table->string('coll_course')->nullable();
            $table->string('coll_gwa', 20)->nullable();
            $table->string('coll_awards')->nullable();

            // Survey Questions
            $table->boolean('first_gen_student')->nullable();
            $table->string('family_college_count', 10)->nullable();
            $table->text('future_outlook')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_educations');
    }
};
