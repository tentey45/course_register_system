<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        return view('admin.registrations.index');
    }
}
