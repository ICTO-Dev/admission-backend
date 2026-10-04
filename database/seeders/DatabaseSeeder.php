<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Campuses
        $this->call(CampusSeeder::class);

        // 2. Default Admin User
        $piliCampus = Campus::where('name', 'Pili')->first() ?? Campus::first();
        User::firstOrCreate(
            ['email' => 'admin@cbsua.edu.ph'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password123'),
                'role_id' => 1,
                'campus_id' => $piliCampus?->id ?? 1,
            ]
        );

        // 3. Courses, Users, Exam Schedules, and Applications
        $this->call([
            CourseSeeder::class,
            UserSeeder::class,
            ExamSchedulingSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
