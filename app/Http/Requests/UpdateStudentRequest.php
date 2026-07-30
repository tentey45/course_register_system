<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'student_id' => ['required', 'string', 'max:30', Rule::unique('students', 'student_id')->ignore($student)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'department_id' => ['required', 'exists:departments,id'],
            'gender' => ['required', 'in:male,female'],
        ];
    }
}
