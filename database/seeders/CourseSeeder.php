<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Program::select('id')->get();
        $faker = \Faker\Factory::create();
        Course::truncate();
        for ($a = 1; $a <= 50; $a++) {
            $data = null;
            $data['course_name'] = $faker->catchPhrase();
            $data['course_code'] = $faker->regexify('[A-Z]{3}[1-9]{3}');
            $data['course_status'] = 'Published';
            $data['course_overview'] = $faker->realText(220);
            $data['hits'] = mt_rand(0, 10000);
            $data['course_program'] = $faker->randomElement($program)->id;
            for ($b = 1; $b < mt_rand(3, 5); $b++) {
                $module = null;
                $module['id'] = $b;
                $module['order'] = $b;
                $module['title'] = "Module $b: " . $faker->catchPhrase();
                for ($c = 1; $c < mt_rand(3, 5); $c++) {
                    $activity = null;
                    $activity['id'] = $c;
                    $activity['order'] = $c;
                    $activity['title'] = "Week $c: {$faker->catchPhrase()}";
                    for ($d = 1; $d <= mt_rand(5, 15); $d++) {
                        $resource = null;
                        $resource['id'] = $d;
                        $resource['title'] = $faker->catchPhrase();
                        $resource['type'] = $faker->randomElement(['Books', 'Articles', 'Newspapers & Industry report', 'Teaching methods', 'Learning methods', 'Tools', 'Techniques']);
                        if (in_array($resource['type'], ['Articles', 'Newspapers & Industry report', 'Techniques'])) {
                            $resource['title'] = $resource['title'] . " " . $faker->url();
                        }
                        $activity['resources_list'][] = $resource;
                    }
                    $module['module_activities'][] = $activity;
                }
                $data['resource_by_topics'][] = $module;
            }
            for ($e = 1; $e < mt_rand(3, 5); $e++) {
                $activity = null;
                $activity['id'] = $e;
                $activity['order'] = $c;
                $activity['title'] = "Skills $e: {$faker->jobTitle()}";
                for ($f = 1; $f <= mt_rand(5, 15); $f++) {
                    $resource = null;
                    $resource['id'] = $f;
                    $resource['title'] = $faker->catchPhrase();
                    $resource['type'] = $faker->randomElement(['Books', 'Articles', 'Newspapers & Industry report', 'Teaching methods', 'Learning methods', 'Tools', 'Techniques']);
                    if (in_array($resource['type'], ['Articles', 'Newspapers & Industry report', 'Techniques'])) {
                        $resource['title'] = $resource['title'] . " " . $faker->url();
                    }
                    $activity['resources_list'][] = $resource;
                }
                $data['resource_by_skills'][] = $activity;
            }
            Course::create($data);
        }
    }
}
