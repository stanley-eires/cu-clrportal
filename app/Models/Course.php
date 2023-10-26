<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_code', 'course_name', 'course_status', 'course_program', 'course_overview', 'course_banner', 'resource_by_topics', 'resource_by_skills', 'course_updated_by', 'hits'
    ];
    protected $attributes = [
        'resource_by_topics' => '[]',
        'resource_by_skills' => '[]',
    ];
    protected $casts = [
        'resource_by_topics' => 'array',
        'resource_by_skills' => 'array',
    ];
}
