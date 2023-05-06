<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class Admin extends Controller
{
    public function overview()
    {
        $counts = ((array)DB::table('programs')->selectRaw('(SELECT COUNT(id) FROM programs) AS programs, (SELECT COUNT(id) FROM departments) AS departments,(SELECT COUNT(id) FROM courses) AS courses')->first());
        $data['title'] = "At a glance";
        $stats = [
            'courses_by_view' => Course::select('course_name', 'hits')->orderBy('hits', 'desc')->take(10)->get(),
            'program_by_view' => Program::leftJoin('courses', 'courses.course_program', '=', 'programs.id')->selectRaw('sum(hits) as program_hits, program_name')->orderBy('program_hits', 'desc')->groupBy('program_name')->take(10)->get(),
            'users_by_group' => User::selectRaw('user_group,count(id) as total_count')->groupBy('user_group')->get(),
            'users_login' => User::selectRaw('name,login_at')->orderBy('login_at', 'desc')->limit(5)->get()
        ];
        $data['data'] = array_merge($stats, $counts);
        return Inertia::render('Overview', $data);
    }

    /** USERS CONTROL */
    public function users(Request $request)
    {
        $data['title'] = "Manage Users";
        $data['users'] = User::orderBy('name')->paginate(20);
        return Inertia::render('Users', $data);
    }
    public function user_save(Request $request)
    {
        $request->id ? User::where('id', $request->post('id'))->update($request->post())
            : User::create($request->post());
        return back()->with('message', [
            'content' => $request->id ? "User details updated" : "New user created", 'status' => 'success'
        ]);
    }
    public function user_bulk_actions(Request $request, $action = null)
    {
        if ($action && $action == 'delete') {
            User::whereIn('id', $request->id)->delete($request->id);
        } else {
            User::whereIn('id', $request->id)->update($request->except(['id']));
        }
        return back();
    }
    public function download_users()
    {
        $tmp = public_path('users.csv');
        $users = User::select('name', 'email', 'created_at', 'login_at', 'status')->orderBy('name')->get()->toArray();
        $fp = fopen($tmp, 'w+');
        foreach ($users as $row) {
            if (empty($header)) {
                $header = array_keys($row);
                fputcsv($fp, $header);
                $header = array_flip($header);
            }
            fputcsv($fp, array_merge($header, $row));
        }
        fclose($fp);
        return response()->download($tmp);
    }
    public function upload_users(Request $request)
    {
        $file = fopen($request->file('csv_file'), "r");
        $users = [];
        $header = array_map(fn ($f) => trim($f), fgetcsv($file));
        foreach (['email', 'name'] as $field) {
            if (!in_array($field, $header)) {
                return back()->with('message', ['content' => "The <strong class='text-uppercase'>[$field]</strong> column is missing in your csv document. This is a required field", 'status' => "error"]);
            }
        }
        while (!feof($file)) {
            $item = [];
            try {
                $line = fgetcsv($file);
                if (empty($line[0]) || empty($line[1])) {
                    continue;
                }
                for ($i = 0; $i <= count($header); $i++) {
                    $item[$header[$i]] = trim($line[$i]);
                    if ($header[$i] == 'created_at') {
                        $item['created_at'] = date('Y-m-d H:i:s', $line[$i] ? strtotime(trim($line[$i])) : time());
                    }
                    if ($header[$i] == 'status') {
                        $item['status'] = trim($line[$i]) ?? 1;
                    }
                }
            } catch (\Throwable $th) {
            }
            if (count($item) !== 0) {
                $users[] = $item;
            }
        }
        DB::table('users')->upsert($users, 'email', ['name', 'email']);
        return back();
    }

    // PROGRAMS CONTROL
    public function programs()
    {
        $data['title'] = "Manage Programs";
        $data['programs'] = Program::select('programs.id', 'programs.program_name', 'programs.department_code', 'degree', 'program_status')->withCount('courses')->orderBy('programs.program_name')->get();
        $data['departments'] = Department::orderBy('department_name')->get();
        return Inertia::render('Resources/Programs', $data);
    }
    public function program_save(Request $request)
    {
        $request->id ? Program::where('id', $request->post('id'))->update($request->post())
            : Program::create($request->post());
        return back()->with('message', [
            'content' => $request->id ? "Program details updated" : "New Program created", 'status' => 'success'
        ]);
    }
    public function program_bulk_actions(Request $request, $action = null)
    {
        if ($action && $action == 'delete') {
            Program::whereIn('id', $request->id)->delete($request->id);
        } else {
            Program::whereIn('id', $request->id)->update($request->except(['id']));
        }
        return back();
    }

    // COURSES CONTROL
    public function courses()
    {
        $data['title'] = "All Courses";
        $data['courses'] = Course::leftJoin('programs', 'courses.course_program', '=', 'programs.id')->select('courses.id', 'course_name', 'course_status', 'program_name', 'courses.created_at', 'courses.updated_at', 'hits', 'course_code', 'resource_by_topics', 'resource_by_skills')->orderBy('course_name')->paginate(20);
        return Inertia::render('Resources/Courses', $data);
    }

    public function course_bulk_actions(Request $request, $action = null)
    {
        $action && $action == 'delete' ? Course::whereIn('id', $request->id)->delete($request->id) : Course::whereIn('id', $request->id)->update($request->except(['id']));
        return back();
    }
    public function course_create()
    {
        $data['course'] = [];
        $data['title'] = 'Create Course';
        $data['programs'] = Program::join('departments', 'departments.department_code', '=', 'programs.department_code')->select('programs.id', 'departments.department_name', 'programs.program_name')->orderBy('program_name')->get();
        return Inertia::render('Resources/CourseBuilder', $data);
    }
    public function course_edit($id)
    {
        $data['course'] = Course::findOrFail($id);
        $data['title'] = '[Edit] ' . $data['course']->course_name;
        $data['programs'] = Program::join('departments', 'departments.department_code', '=', 'programs.department_code')->select('programs.id', 'departments.department_name', 'programs.program_name')->orderBy('program_name')->get();
        return Inertia::render('Resources/CourseBuilder', $data);
    }
    public function course_save(Request $request, $id = null)
    {
        $action = $request->action;
        $input = $request->except(['action', '_method']);
        if ($request->hasFile('course_banner') && $request->file('course_banner')->isValid()) {
            $input['course_banner'] = $request->file('course_banner')->storeAs(date('Y'), $request->file('course_banner')->getClientOriginalName(), 'public');
        }
        if ($id) {
            Course::where('id', $id)->update($input);
        } else {
            $id = Course::create($input);
        }
        if ($action == 'close') {
            return to_route('admin.courses');
        } elseif ($action == 'new') {
            return to_route('admin.course.create');
        } elseif ($action == 'save') {
            return to_route('admin.course.edit', [$id])->with('message', ['content' => "Course Saved", 'status' => 'success']);
        } elseif ($action == 'copy') {
            $request_array = $request->except(['action']);
            $request_array['course_name'] = $request_array['course_name'] . " (Copy)";
            $id = Course::create($request_array);
            return to_route('admin.course.edit', [$id]);
        }
        return back();
    }

    public function maintenanceFunctions(Request $request)
    {
        $type = $request->type;
        $content = null;
        if ($type == 'clear_cache') {
            $exitCode = Artisan::call("cache:clear");
            $exitCode = Artisan::call("route:clear");
            $exitCode = Artisan::call("config:clear");
            $content = "Site cache have been cleared.";
        } elseif ($type == 'optimize') {
            $exitCode = Artisan::call("route:cache");
            $exitCode = Artisan::call("config:cache");
            $content = "Some files have been cached";
        } elseif ($type == 'create_symlink') {
            $exitCode = Artisan::call("storage:link");
            $content = "Symlink created";
        } elseif ($type == 'reset_platform') {
            User::where('id', '!=', Auth::id())->forceDelete();
            Course::truncate();
            Department::truncate();
            Program::truncate();
            $content = "Tables Truncated. You are now the only user on the platform";
        }
        return back()->with('message', [
            'content' => $content,
            'status' => 'success',
        ]);
    }
}
