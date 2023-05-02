<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('course_name');
            $table->string('course_code');
            $table->enum('course_status', ['Published', 'Unpublished'])->default('Published');
            $table->integer('course_program')->nullable();
            $table->string('course_overview')->nullable();
            $table->string('course_banner')->nullable();
            $table->json('resource_by_topics');
            $table->json('resource_by_skills');
            $table->integer('course_updated_by')->nullable();
            $table->integer('hits')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
