<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PublicController;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PublicController::class, 'index'])->name('public.courses');
Route::get('/course/{id}', [PublicController::class, 'course'])->name('public.course.single');
Route::post('login', [PublicController::class, 'handleLogin'])->name('login');
Route::post('logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return to_route('public.courses')->with('message', ['content' => 'You have been logged out', 'status' => 'success']);
})->name('logout');

Route::prefix('administrator')->group(function () {
    Route::get('/overview', [Admin::class, 'overview'])->name('admin.overview')->middleware('mustlogin:admin,author');
    Route::middleware('mustlogin:author')->group(function () {

        Route::get('/courses', [Admin::class, 'courses'])->name('admin.courses');
        Route::put('/courses/{action?}', [Admin::class, 'course_bulk_actions'])->name('admin.courses.bulk-actions');
        Route::get('/course/create', [Admin::class, 'course_create'])->name('admin.course.create');
        Route::get('/course/{id}', [Admin::class, 'course_edit'])->name('admin.course.edit');
        Route::post('/course/{id?}', [Admin::class, 'course_save'])->name('admin.course.save');

        Route::get('/programs', [Admin::class, 'programs'])->name('admin.programs');
        Route::post('/programs', [Admin::class, 'program_save'])->name('admin.programs.save');
        Route::put('/programs/{action?}', [Admin::class, 'program_bulk_actions'])->name('admin.program.bulk-actions');
    });
    Route::middleware('mustlogin:admin')->group(function () {
        Route::get('/users', [Admin::class, 'users'])->name('admin.users');
        Route::post('/users', [Admin::class, 'user_save'])->name('admin.user.save');
        Route::put('/users/{action?}', [Admin::class, 'user_bulk_actions'])->name('admin.users.bulk-actions');
        Route::get('/users/download', [Admin::class, 'download_users'])->name('admin.users.download');
        Route::post('/users/upload', [Admin::class, 'upload_users'])->name('admin.users.upload');

        Route::get('/roles', [Admin::class, 'rolesAndPermissions'])->name('admin.roles');
        Route::post('/roles', [Admin::class, 'saveRolesAndPermissions'])->name('admin.roles.save');
    });
    Route::match(['get', 'post'], '/maintenance-functions', [Admin::class, 'maintenanceFunctions'])->name('admin.maintenance-functions')->middleware('mustlogin:admin');
});

Route::prefix('seedings')->group(
    function () {
        Route::post('courses', fn () => Artisan::call('db:seed --class=CourseSeeder'))->name('seedings.courses');
        Route::post('departments', fn () => Artisan::call('db:seed --class=DepartmentSeeder'))->name('seedings.departments');
        Route::post('programs', fn () => Artisan::call('db:seed --class=ProgramSeeder'))->name('seedings.programs');
        Route::post('users', fn () => Artisan::call('db:seed --class=UserSeeder'))->name('seedings.users');

        Route::get('122587-reset', fn () => Artisan::call('migrate:fresh  --seed'));
    }
);

Route::get('/batchwork', function () {
    $courses = Course::select("id", "resource_by_topics", "resource_by_skills")->get();
    foreach ($courses as $course) {
        // GET THE RESOURCE BY TOPICS
        $resource_by_topics = $course->resource_by_topics;
        foreach ($resource_by_topics as $module) {
            $module_activities_array = [];
            $module_activities = $module['module_activities'];
            foreach ($module_activities as $week) {
                dd($week);
                $resource_list_array = [];
                $resources_list = $week['resources_list'];
                foreach ($resources_list as $list) {
                    $resource = [];
                    $resource['id'] = $list['id'];
                    $resource['title'] = $list['title'];
                    if ($list['type'] == "Articles") {
                        $resource['type'] = "Journal Articles";
                    } elseif ($list['type'] == "Newspapers & Industry report") {
                        $resource['type'] = "Newspaper Articles";
                    } elseif ($list['type'] == "Tools") {
                        $resource['type'] = "Software & Tools";
                    } elseif ($list['type'] == "Teaching methods") {
                        $resource['type'] = "Workshops & Trainings";
                    } else {
                        $resource['type'] = "Books";
                    }
                    $resource_list_array[] = $resource;
                }
            }
            dd($module_activities);
        }
    }
});
