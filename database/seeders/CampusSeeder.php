<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            [
                'name' => 'Pili',
                'aname' => 'PIL',
                'campus_address' => 'San Jose, Pili, Camarines Sur 4418',
            ],
            [
                'name' => 'Calabanga',
                'aname' => 'CAL',
                'campus_address' => 'Ratay, Calabanga, Camarines Sur 4405',
            ],
            [
                'name' => 'Pasacao',
                'aname' => 'PAS',
                'campus_address' => 'Sta. Rosa Del Norte Pasacao, Camarines Sur 4417',
            ],
            [
                'name' => 'Sipocot',
                'aname' => 'SIP',
                'campus_address' => 'Zone 5 Impig, Sipocot, Camarines Sur 4408',
            ],
        ];

        foreach ($campuses as $campusData) {
            Campus::firstOrCreate(
                ['name' => $campusData['name']],
                $campusData
            );
        }
    }
}
