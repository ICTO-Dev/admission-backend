<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@cbsua.edu.ph'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password123'),
                'role_id' => 1,
                'campus_id' => 1,
            ]
        );

        $this->call(CampusSeeder::class);
        $this->call(CourseSeeder::class);
    }
}
