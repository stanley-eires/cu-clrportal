<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['program_name' => 'Petroleum Engineering', 'department_code' => 'PET'],
            ['program_name' => 'Chemical Engineering', 'department_code' => 'CHE'],
            ['program_name' => 'Sociology', 'department_code' => 'SOC'],
            ['program_name' => 'Mass Communication', 'department_code' => 'MAC'],
            ['program_name' => 'Economics', 'department_code' => 'ECO'],
            ['program_name' => 'Demography and Social Statistics', 'department_code' => 'ECO'],
            ['program_name' => 'Accounting', 'department_code' => 'ACC'],
            ['program_name' => 'Banking and Finance', 'department_code' => 'BFN'],
            ['program_name' => 'Business Administration', 'department_code' => 'BUS'],
            ['program_name' => 'Marketing', 'department_code' => 'BUS'],
            ['program_name' => 'Industrial Relations and HRM', 'department_code' => 'BUS'],
            ['program_name' => 'Entrepreneurship', 'department_code' => 'BUS'],
            ['program_name' => 'Applied Biology and Biotechnology', 'department_code' => 'BLY'],
            ['program_name' => 'Animal and Environmental Biology', 'department_code' => 'BLY'],
            ['program_name' => 'Microbiology', 'department_code' => 'BLY'],
            ['program_name' => 'Architecture', 'department_code' => 'ARC'],
            ['program_name' => 'Estate Management', 'department_code' => 'ESM'],
            ['program_name' => 'Computer Science', 'department_code' => 'CIS'],
            ['program_name' => 'Management and Information Science', 'department_code' => 'CIS'],
            ['program_name' => 'Mathematics', 'department_code' => 'MAT'],
            ['program_name' => 'Biochemistry', 'department_code' => 'BCH'],
            ['program_name' => 'Chemistry', 'department_code' => 'CHM'],
            ['program_name' => 'Building Technology', 'department_code' => 'BLD'],
            ['program_name' => 'Physics', 'department_code' => 'PHY'],
            ['program_name' => 'Political Science & International Relations', 'department_code' => 'POL'],
            ['program_name' => 'Psychology', 'department_code' => 'PSY'],
            ['program_name' => 'English', 'department_code' => 'LGE'],
            ['program_name' => 'French', 'department_code' => 'LGE'],
            ['program_name' => 'Leadership Studies', 'department_code' => 'LDS'],
            ['program_name' => 'Mechanical Engineering', 'department_code' => 'MCE'],
            ['program_name' => 'Civil Engineering', 'department_code' => 'CVE'],
            ['program_name' => 'Computer Engineering', 'department_code' => 'EIE'],
            ['program_name' => 'Electrical and Electronics Engineering', 'department_code' => 'EIE'],
            ['program_name' => 'Information and Communication Technology', 'department_code' => 'EIE'],
        ];
        Program::truncate();
        Program::insert($data);
    }
}
