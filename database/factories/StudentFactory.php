<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'student_id' => date('Y') . strtoupper(Str::random(5)),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'department_id' => Department::factory(),
            'gender' => fake()->randomElement(['male', 'female']),
            'google_id' => null,
            'avatar' => null,
        ];
    }
}
