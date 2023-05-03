<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PublicController;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
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
Route::get('/course/{id}', [PublicController::class, 'course'])->name('public.course.single')->middleware('mustlogin:reader');
Route::post('login', [PublicController::class, 'handleLogin'])->name('login');
Route::post('logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return to_route('public.courses')->with('message', ['content' => 'You have been logged out', 'status' => 'success']);
})->name('logout');

Route::prefix('administrator')->group(function () {
    Route::get('/overview', [Admin::class, 'overview'])->name('admin.overview');
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
    });
    Route::post('/maintenance-functions', [Admin::class, 'maintenanceFunctions'])->name('admin.maintenance-functions')->middleware('mustlogin:admin');
});

Route::prefix('seedings')->group(
    function () {
        Route::post('courses', fn () => Course::seed())->name('seedings.courses');
        Route::post('departments', fn () => Department::seed())->name('seedings.departments');
        Route::post('programs', fn () => Program::seed())->name('seedings.programs');
        Route::post('users', fn () => User::seed())->name('seedings.users');
    }
);
