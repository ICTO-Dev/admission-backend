<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Campus;
use App\Models\ExamSchedule;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class ExamSchedulingSeeder extends Seeder
{
    /**
     * Run the database seeds for exam scheduling.
     */
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin?->id ?? 1;

        // 1. Seed School Years
        $sy2026 = SchoolYear::firstOrCreate(
            ['name' => '2026-2027'],
            [
                'is_active' => true,
                'status' => 'Active',
                'user_id' => $adminId,
            ]
        );

        SchoolYear::firstOrCreate(
            ['name' => '2025-2026'],
            [
                'is_active' => false,
                'status' => 'Closed',
                'user_id' => $adminId,
            ]
        );

        $campuses = Campus::all();
        $piliCampus = $campuses->firstWhere('name', 'Pili') ?? $campuses->first();

        if (!$piliCampus) {
            return;
        }

        // 2. Seed Venues
        $gym = Venue::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'venue_name' => 'University Gymnasium',
            ],
            [
                'description' => 'Main University Gymnasium for mass testing and examinations',
                'user_id' => $adminId,
            ]
        );

        $avc = Venue::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'venue_name' => 'Audio-Visual Center (AVC)',
            ],
            [
                'description' => 'Air-conditioned multimedia testing hall',
                'user_id' => $adminId,
            ]
        );

        // Seed Venues for other campuses
        foreach ($campuses as $campus) {
            if ($campus->id === $piliCampus->id) continue;

            Venue::firstOrCreate(
                [
                    'campus_id' => $campus->id,
                    'venue_name' => "{$campus->name} Campus Multi-Purpose Hall",
                ],
                [
                    'description' => "Official examination and event facility of {$campus->name} Campus",
                    'user_id' => $adminId,
                ]
            );
        }

        // 3. Seed Rooms
        $roomGymA = Room::firstOrCreate(
            [
                'venue_id' => $gym->id,
                'room_name' => 'Gym Court A',
            ],
            [
                'total_seat' => 35,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        $roomGymB = Room::firstOrCreate(
            [
                'venue_id' => $gym->id,
                'room_name' => 'Gym Court B',
            ],
            [
                'total_seat' => 35,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        $roomAvc1 = Room::firstOrCreate(
            [
                'venue_id' => $avc->id,
                'room_name' => 'AVC Room 101',
            ],
            [
                'total_seat' => 30,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        $roomAvc2 = Room::firstOrCreate(
            [
                'venue_id' => $avc->id,
                'room_name' => 'AVC Room 102',
            ],
            [
                'total_seat' => 30,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        // 4. Seed Batches
        $batch1 = Batch::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'school_year_id' => $sy2026->id,
                'batch_name' => 'Batch 1 - Morning (08:00 AM - 11:00 AM)',
            ],
            [
                'status' => 'Active',
                'user_id' => $adminId,
            ]
        );

        $batch2 = Batch::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'school_year_id' => $sy2026->id,
                'batch_name' => 'Batch 2 - Afternoon (01:00 PM - 04:00 PM)',
            ],
            [
                'status' => 'Active',
                'user_id' => $adminId,
            ]
        );

        // 5. Seed Exam Schedules (Slots)
        ExamSchedule::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'school_year_id' => $sy2026->id,
                'batch_id' => $batch1->id,
                'room_id' => $roomGymA->id,
                'exam_date' => '2026-10-24',
            ],
            [
                'day_label' => 'Day 1 - Morning Session',
                'start_time' => '08:00:00',
                'end_time' => '11:00:00',
                'max_capacity' => 35,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        ExamSchedule::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'school_year_id' => $sy2026->id,
                'batch_id' => $batch2->id,
                'room_id' => $roomGymA->id,
                'exam_date' => '2026-10-24',
            ],
            [
                'day_label' => 'Day 1 - Afternoon Session',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'max_capacity' => 35,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );

        ExamSchedule::firstOrCreate(
            [
                'campus_id' => $piliCampus->id,
                'school_year_id' => $sy2026->id,
                'batch_id' => $batch1->id,
                'room_id' => $roomAvc1->id,
                'exam_date' => '2026-10-25',
            ],
            [
                'day_label' => 'Day 2 - Morning Session',
                'start_time' => '08:00:00',
                'end_time' => '11:00:00',
                'max_capacity' => 30,
                'status' => 'Available',
                'user_id' => $adminId,
            ]
        );
    }
}
