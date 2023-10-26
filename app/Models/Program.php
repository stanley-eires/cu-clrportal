<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name', 'department_code', 'degree', 'program_status'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_program');
    }
}
