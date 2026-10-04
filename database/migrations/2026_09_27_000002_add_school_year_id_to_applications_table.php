<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('school_year_id')
                ->nullable()
                ->after('application_no')
                ->constrained('school_years')
                ->nullOnDelete();
        });

        // Determine default active/open school year
        $openSyId = DB::table('school_years')
            ->where('status', 'Open')
            ->where('is_active', true)
            ->value('id')
            ?? DB::table('school_years')->value('id')
            ?? 1;

        if (Schema::hasColumn('applications', 'school_year')) {
            // Update applications by matching school_year string with school_years.name
            $schoolYears = DB::table('school_years')->get();
            foreach ($schoolYears as $sy) {
                DB::table('applications')
                    ->where('school_year', $sy->name)
                    ->update(['school_year_id' => $sy->id]);
            }

            // Fallback for any unassigned
            DB::table('applications')
                ->whereNull('school_year_id')
                ->update(['school_year_id' => $openSyId]);

            // Drop legacy text string column
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('school_year');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('school_year')->default('2026-2027')->after('application_no');
        });

        $applications = DB::table('applications')->whereNotNull('school_year_id')->get();
        foreach ($applications as $app) {
            $syName = DB::table('school_years')->where('id', $app->school_year_id)->value('name');
            if ($syName) {
                DB::table('applications')->where('id', $app->id)->update(['school_year' => $syName]);
            }
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_year_id');
        });
    }
};
