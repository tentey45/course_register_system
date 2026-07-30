<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'string', 'max:30', 'unique:students,student_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['required', 'exists:departments,id'],
            'gender' => ['required', 'in:male,female'],
        ];
    }
}
