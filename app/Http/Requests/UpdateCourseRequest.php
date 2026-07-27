<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_code' => [
                'required', 'string', 'max:20',
                Rule::unique('courses', 'course_code')->ignore($this->route('course')),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer', 'min:1', 'max:10'],
            'department_id' => ['required', 'exists:departments,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
        ];
    }
}