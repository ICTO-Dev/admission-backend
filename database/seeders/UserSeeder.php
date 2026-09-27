<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'pili.admission@cbsua.edu.ph',
                'name' => 'Pili Admission Officer',
                'campus_name' => 'Pili',
                'role_id' => 1,
            ],
            [
                'email' => 'calabanga.admission@cbsua.edu.ph',
                'name' => 'Calabanga Admission Officer',
                'campus_name' => 'Calabanga',
                'role_id' => 1,
            ],
            [
                'email' => 'pasacao.admission@cbsua.edu.ph',
                'name' => 'Pasacao Admission Officer',
                'campus_name' => 'Pasacao',
                'role_id' => 1,
            ],
            [
                'email' => 'sipocot.admission@cbsua.edu.ph',
                'name' => 'Sipocot Admission Officer',
                'campus_name' => 'Sipocot',
                'role_id' => 1,
            ],
        ];

        foreach ($users as $userData) {
            $campus = Campus::where('name', $userData['campus_name'])->first();

            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password123'),
                    'role_id' => $userData['role_id'],
                    'campus_id' => $campus?->id,
                ]
            );
        }
    }
}
