<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        Auth::loginUsingId(1);
        $data['programs'] = cache()->remember('programs_with_course_count', now()->addMinutes(2), function () {
            return  Program::select('programs.id', 'programs.program_name', 'programs.department_code')->withCount('courses')->where('program_status', 'Published')->orderBy('programs.program_name')->get();
        });
        $courses = Course::leftJoin('programs', 'programs.id', '=', 'courses.course_program')->select('course_name', 'course_code', 'course_overview', 'course_banner', 'courses.id', 'program_name', 'programs.id as program_id');
        if ($request->search) {
            $courses = $courses
                ->where(
                    fn ($query) =>
                    $query->where('course_name', 'like', "%{$request->search}%")
                        ->orWhere('course_code', 'like', "%{$request->search}%")
                );
        }
        if ($request->programs) {
            $courses = $courses->whereIn('programs.id', $request->programs);
        }
        $data['courses'] = $courses->where('course_status', 'Published')->orderBy('course_name')->paginate(20)->withQueryString();
        $data['title'] = "All Courses";
        return Inertia::render('Public/Courses', $data);
    }
    public function course($id)
    {
        $data['course'] = cache()->remember(
            "course_single_{$id}",
            now()->addMinutes(2),
            fn () => Course::leftJoin('programs', 'programs.id', '=', 'courses.course_program')->select('courses.id', 'course_name', 'course_code', 'course_overview', 'course_banner', 'resource_by_topics', 'resource_by_skills', 'hits', 'program_name')->findOrFail($id)
        );
        Course::where('id', $data['course']->id)->update(['hits' => $data['course']->hits + 1]);
        $data['title'] = $data['course']->course_name;
        return Inertia::render('Public/Course', $data);
    }

    public function handleLogin(Request $request)
    {
        $email = $request->email;
        $user = User::where(['email' => $email])->first();
        if ($user) {
            Auth::login($user);
            User::where('id', Auth::id())->update(['login_at' => date("M d, Y h:i A")]);
            return redirect()->intended(route('admin.overview'));
        } else {
            return back()->with('message', ['content' => "We could not identify you on our platform, kindly contact <strong><a href='mailto:ifeakachuku.osinulu@covenantuniversity.edu.ng'>Mrs Osinulu (Support)</a></strong> on what to do next", 'status' => 'error']);
        }
    }
}
