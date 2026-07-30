<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        $registrations = Registration::with(['student.department', 'course.department', 'course.semester', 'payment'])
            ->latest('registered_at')->paginate(20);
        return view('admin.registrations.index', compact('registrations'));
    }
}
