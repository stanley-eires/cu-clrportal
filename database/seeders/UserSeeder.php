<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        User::truncate();
        for ($i = 0; $i < 5; $i++) {
            $user = null;
            $user['name'] = $i == 0 ? 'CLR Admin' : $faker->name;
            $user['email'] = $i == 0 ? env('MAIL_FROM_ADDRESS') : $faker->email;
            $user['password'] = Hash::make('password');
            $user['status'] = $i == 0 ? 1 : mt_rand(0, 10) != 0;
            if (mt_rand(0, 1) == 1) {
                $user['login_at'] = date("M d, Y h:i A", strtotime(mt_rand(-10, 0) . ' days'));
            }
            $user = User::create($user);
            if ($i == 0) {
                $user->assignRole(ADMIN);
            } else {
                $user->assignRole($faker->randomElement([ADMIN, EDITOR, READER]));
            }
        }
    }
}
