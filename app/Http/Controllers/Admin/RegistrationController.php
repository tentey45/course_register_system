<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        $registrations = Registration::with(['student', 'course.department'])->get();
        return view('admin.registrations.index', compact('registrations'));
    }
}
