<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['courseName' => 'Bachelor of Agricultural Technology', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => false],
            ['courseName' => 'BS Agroforestry', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture in General Curriculum', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Agriculture Extension', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Agronomy', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Animal Science', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Agricultural Economics', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Entomology', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Farming System', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Plant Pathology', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'BS Agriculture Major in Soil Science', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'Expanded Tertiary Educ Equivalency & Accreditation Prog (ETEEAP)', 'campus_id' => 1, 'status' => 0, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CANR', 'is_open_program' => false],
            ['courseName' => 'BS Agribusiness', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CEM', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in English', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Mathematics', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Science', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Filipino', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Elementary Education', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'Enriched Secondary Education Curriculum', 'campus_id' => 1, 'status' => 0, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CDE', 'is_open_program' => false],
            ['courseName' => 'BS Biology', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CAS', 'is_open_program' => false],
            ['courseName' => 'BS Environmental Science', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CAS', 'is_open_program' => true],
            ['courseName' => 'BS Food Technology (BSFT)', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CEFS', 'is_open_program' => false],
            ['courseName' => 'BS Agricultural and Biosystems Engineering - 4 years', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => 'CEFS', 'is_open_program' => true],
            ['courseName' => 'Enriched Secondary Education', 'campus_id' => 0, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in English', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Mathematics', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Filipino', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Elementary Education', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Industrial Technology Major in Automotive', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Industrial Technology Major in Electrical', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Industrial Technology Major in Electronics', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Industrial Technology Major in Hospitality Management', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Industrial Technology Major in Refrigeration and Air-conditioning', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Arts in English Language', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science in Mathematics', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Science in Fisheries', 'campus_id' => 2, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Secondary Education Major in English', 'campus_id' => 3, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Mathematics', 'campus_id' => 3, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Filipino', 'campus_id' => 3, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Elementary Education', 'campus_id' => 3, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Information Technology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Computer Hardware Servicing NC II', 'campus_id' => 4, 'status' => 0, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Secondary Education (Enriched Curriculum)', 'campus_id' => 4, 'status' => 0, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Criminology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Elementary Education (General)', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in English', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Mathematics', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Science', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Filipino', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science Industrial Technology Major in Automotive Technology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science Industrial Technology Major in Electrical Technology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science Industrial Technology Major in Electronics Technology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science Industrial Technology Major in Food Trade', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science Industrial Technology Major in Mechanical Technology', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-10-27 21:09:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Agriculture Major Horticulture', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-11-07 09:37:00', 'college' => 'CANR', 'is_open_program' => true],
            ['courseName' => 'Doctor of Veterinary Medicine (DVM) - 6 years', 'campus_id' => 1, 'status' => 1, 'date_added' => '2019-11-07 10:40:00', 'college' => 'CVM', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Technology and Livelihood Education (BTLED) Major Home Economics', 'campus_id' => 4, 'status' => 1, 'date_added' => '2019-11-07 12:17:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Secondary Education Major in Science', 'campus_id' => 3, 'status' => 1, 'date_added' => '2019-11-07 12:19:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science in Environmental Science', 'campus_id' => 3, 'status' => 1, 'date_added' => '2021-03-03 08:50:00', 'college' => null, 'is_open_program' => false],
            ['courseName' => 'BS Environmental Science', 'campus_id' => 2, 'status' => 1, 'date_added' => '2021-04-26 14:45:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Science in Environmental Science', 'campus_id' => 4, 'status' => 1, 'date_added' => '2021-04-27 16:42:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Science in Agroforestry', 'campus_id' => 4, 'status' => 1, 'date_added' => '2021-04-27 16:42:00', 'college' => null, 'is_open_program' => true],
            ['courseName' => 'Bachelor of Science in Tourism Management Major in Agritourism', 'campus_id' => 1, 'status' => 1, 'date_added' => '2023-02-07 09:41:00', 'college' => 'CEM', 'is_open_program' => false],
            ['courseName' => 'Bachelor of Science in Marine Biology (BSMBio)', 'campus_id' => 3, 'status' => 1, 'date_added' => '2023-02-07 10:01:00', 'college' => null, 'is_open_program' => false],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                [
                    'courseName' => $courseData['courseName'],
                    'campus_id'  => $courseData['campus_id'],
                ],
                $courseData
            );
        }
    }
}
