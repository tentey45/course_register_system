<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $student = Student::where('google_id', $googleUser->getId())
            ->orWhere('email', strtolower($googleUser->getEmail()))
            ->first();

        if ($student) {
            if (!$student->is_active) {
                return redirect()->route('login')->with('error', 'This student account is inactive. Please contact the registrar.');
            }

            if (!$student->google_id) {
                $student->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            $this->logInStudent($request, $student);
            return redirect()->route('student.dashboard');
        }

        // Brand-new student — we still need a department before we can
        // create the row (department_id is required), so stash the Google
        // profile in the session and send them to a short completion form.
        $request->session()->put('google_pending', [
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName() ?: $googleUser->getNickname(),
            'email' => strtolower($googleUser->getEmail()),
            'avatar' => $googleUser->getAvatar(),
        ]);

        return redirect()->route('register.complete');
        try {

        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();


        dd($googleUser);


    } catch (\Exception $e) {

        dd($e->getMessage());

    }
    }

    public function showCompleteForm(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('google_pending');
        if (!$pending) {
            return redirect()->route('login');
        }

        $departments = Department::orderBy('name')->get();

        return view('auth.complete-profile', compact('pending', 'departments'));
    }

    public function storeCompleteForm(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('google_pending');
        if (!$pending) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'gender' => ['required', 'in:male,female'],
        ]);

        $student = Student::create([
            'student_id' => $this->generateStudentId(),
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => Hash::make(Str::random(40)), // never used — Google-only account
            'department_id' => $validated['department_id'],
            'gender' => $validated['gender'],
            'google_id' => $pending['google_id'],
            'avatar' => $pending['avatar'],
        ]);

        $request->session()->forget('google_pending');
        $this->logInStudent($request, $student);

        return redirect()->route('student.dashboard')->with('success', 'Welcome, ' . $student->name . '!');
    }

    protected function logInStudent(Request $request, Student $student): void
    {
        $request->session()->put([
            'authenticated' => true,
            'user_id' => $student->id,
            'user_name' => $student->name,
            'user_email' => $student->email,
            'student_id' => $student->student_id,
            'department_id' => $student->department_id,
            'role' => 'student',
        ]);
    }

    protected function generateStudentId(): string
    {
        do {
            $candidate = date('Y') . strtoupper(Str::random(5));
        } while (Student::where('student_id', $candidate)->exists());

        return $candidate;
    }
}
