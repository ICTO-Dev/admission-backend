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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('courseName', 255);
            $table->unsignedBigInteger('campus_id')->default(0);
            $table->integer('status')->default(1);
            $table->string('college', 45)->nullable();
            $table->boolean('is_open_program')->default(false);
            $table->timestamp('date_added')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
