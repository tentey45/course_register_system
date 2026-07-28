<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCourseRequest;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));

        $courses = Course::with(['department', 'semester'])
            ->when($search, function ($query, $search) {
                $query->where('course_code', 'LIKE', "%{$search}%")
                      ->orWhere('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->get();

        return view('student.courses.index', compact('courses', 'search'));
    }

    public function show(Request $request, string $course): View
    {
        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->with(['department', 'semester', 'schedules'])
            ->firstOrFail();

        $studentId = $request->session()->get('user_id', 1);

        $registration = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->first();

        $isRegistered = $registration && $registration->status === Registration::STATUS_REGISTERED;
        $isPendingPayment = $registration && $registration->status === Registration::STATUS_PENDING_PAYMENT;

        $registeredCount = Registration::where('course_id', $courseModel->id)
            ->where('status', Registration::STATUS_REGISTERED)
            ->count();

        return view('student.courses.show', [
            'course' => $courseModel,
            'isRegistered' => $isRegistered,
            'isPendingPayment' => $isPendingPayment,
            'registeredCount' => $registeredCount,
        ]);
    }

    public function register(RegisterCourseRequest $request, string $course): RedirectResponse
    {
        $studentId = $request->session()->get('user_id', 1);

        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->firstOrFail();

        $existing = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'registered') {
                return redirect()->route('student.courses.show', $courseModel->course_code)
                    ->with('error', 'You are already registered for this course.');
            }
            $existing->update(['status' => 'registered', 'registered_at' => now()]);
        } else {
            Registration::create([
                'student_id' => $studentId,
                'course_id' => $courseModel->id,
                'registered_at' => now(),
                'status' => 'registered',
            ]);
        }

        return redirect()->route('student.courses.my-courses')
            ->with('success', "Successfully registered for {$courseModel->course_code} - {$courseModel->title}!");
    }

    public function myCourses(Request $request): View
    {
        $studentId = $request->session()->get('user_id', 1);

        $registrations = Registration::where('student_id', $studentId)
            ->where('status', 'registered')
            ->with(['course.department', 'course.semester', 'course.schedules'])
            ->get();

        return view('student.courses.my-courses', compact('registrations'));
    }

    public function schedule(Request $request): View
    {
        $studentId = $request->session()->get('user_id', 1);

        $courseIds = Registration::where('student_id', $studentId)
            ->where('status', 'registered')
            ->pluck('course_id');

        $schedules = CourseSchedule::whereIn('course_id', $courseIds)
            ->with('course')
            ->get()
            ->groupBy('day_of_week');

        return view('student.courses.schedule', compact('schedules'));
    }
}
