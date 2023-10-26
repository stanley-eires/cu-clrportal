<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['department_name' => 'Civil Engineering', 'department_code' => 'CVE', 'college' => 'Engineering'],
            ['department_name' => 'Electrical and Information Engineering', 'department_code' => 'EIE', 'college' => 'Engineering'],
            ['department_name' => 'Mechanical Engineering', 'department_code' => 'MCE', 'college' => 'Engineering'],
            ['department_name' => 'Petroleum Engineering', 'department_code' => 'PET', 'college' => 'Engineering'],
            ['department_name' => 'Chemical Engineering', 'department_code' => 'CHE', 'college' => 'Engineering'],

            ['department_name' => 'Political Science and International Relations', 'department_code' => 'POL', 'college' => 'Leadership and Development Studies'],
            ['department_name' => 'Psychology', 'department_code' => 'PSY', 'college' => 'Leadership and Development Studies'],
            ['department_name' => 'Languages and General Studies', 'department_code' => 'LGE', 'college' => 'Leadership and Development Studies'],
            ['department_name' => 'Leadership Studies', 'department_code' => 'LDS', 'college' => 'Leadership and Development Studies'],

            ['department_name' => 'Accounting', 'department_code' => 'ACC', 'college' => 'Management and Social Sciences'],
            ['department_name' => 'Banking and Finance', 'department_code' => 'BFN', 'college' => 'Management and Social Sciences'],
            ['department_name' => 'Business Management', 'department_code' => 'BUS', 'college' => 'Management and Social Sciences'],
            ['department_name' => 'Mass Communication', 'department_code' => 'MAC', 'college' => 'Management and Social Sciences'],
            ['department_name' => 'Sociology', 'department_code' => 'SOC', 'college' => 'Management and Social Sciences'],

            ['department_name' => 'Architecture', 'department_code' => 'ARC', 'college' => 'Science and Technology'],
            ['department_name' => 'Building Technology', 'department_code' => 'BLD', 'college' => 'Science and Technology'],
            ['department_name' => 'Estate Management', 'department_code' => 'ESM', 'college' => 'Science and Technology'],
            ['department_name' => 'Biological Sciences', 'department_code' => 'BLY', 'college' => 'Science and Technology'],
            ['department_name' => 'Biochemistry', 'department_code' => 'BCH', 'college' => 'Science and Technology'],
            ['department_name' => 'Chemistry', 'department_code' => 'CHM', 'college' => 'Science and Technology'],
            ['department_name' => 'Computer and Information Sciences', 'department_code' => 'CIS', 'college' => 'Science and Technology'],
            ['department_name' => 'Mathematics', 'department_code' => 'MAT', 'college' => 'Science and Technology'],
            ['department_name' => 'Physics', 'department_code' => 'PHY', 'college' => 'Science and Technology'],
        ];
        Department::truncate();
        Department::insert($data);
    }
}
