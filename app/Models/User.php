<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'status', 'password', 'login_at', 'roles', 'user_group'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $attributes = [
        'roles' =>  '["reader"]',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
        'created_at' => 'datetime',
    ];

    public static function seed()
    {
        self::truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $user = null;
            $user['name'] = $i == 0 ? 'CLR Admin' : $faker->name;
            $user['email'] = $i == 0 ? env('MAIL_FROM_ADDRESS') : $faker->email;
            if ($i == 0) {
                $user['roles'] =  ["admin", "author", "reader"];
            }
            $user['user_group'] = $faker->randomElement(['Faculty', 'Staff', 'Student', 'Others']);
            $user['password'] = Hash::make('password');
            $user['status'] = $i == 0 ? 1 : mt_rand(0, 10) != 0;
            if (mt_rand(0, 1) == 1) {
                $user['login_at'] = date("M d, Y h:i A", strtotime(mt_rand(-10, 0) . ' days'));
            }
            self::create($user);
        }
        back();
    }
}
