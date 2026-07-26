<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Department;
use App\Models\Registration;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $csDept = Department::create(['code' => 'CS', 'name' => 'Computer Science']);
        $itDept = Department::create(['code' => 'IT', 'name' => 'Information Technology']);
        $seDept = Department::create(['code' => 'SE', 'name' => 'Software Engineering']);

        // 2. Seed Active Semester
        $semester = Semester::create([
            'name' => 'Semester 1',
            'academic_year' => 'Year 2026',
            'is_active' => true,
        ]);

        // 3. Seed Admin User
        Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@scrs.edu',
            'password' => Hash::make('password123'),
        ]);

        // 4. Seed Demo Student
        $student = Student::create([
            'student_id' => '00124875',
            'name' => 'John Doe',
            'email' => 'student@scrs.edu',
            'password' => Hash::make('password123'),
            'department_id' => $csDept->id,
            'gender' => 'male',
        ]);

        // Additional sample students
        Student::create([
            'student_id' => '00124876',
            'name' => 'Jane Smith',
            'email' => 'jane.smith@scrs.edu',
            'password' => Hash::make('password123'),
            'department_id' => $csDept->id,
            'gender' => 'female',
        ]);

        // 5. Seed Courses
        $c1 = Course::create([
            'course_code' => 'CS201',
            'title' => 'Data Structures & Algorithms',
            'description' => 'Fundamental data structures including arrays, stacks, queues, trees, graphs, sorting and searching algorithms.',
            'credits' => 3,
            'department_id' => $csDept->id,
            'semester_id' => $semester->id,
            'capacity' => 40,
        ]);

        $c2 = Course::create([
            'course_code' => 'CS202',
            'title' => 'Database Systems',
            'description' => 'Relational database model, SQL query optimization, database design normalization, indexing, and MySQL management.',
            'credits' => 3,
            'department_id' => $csDept->id,
            'semester_id' => $semester->id,
            'capacity' => 40,
        ]);

        $c3 = Course::create([
            'course_code' => 'CS301',
            'title' => 'Web Application Development',
            'description' => 'Full-stack web application development using Laravel framework, Blade templating, REST APIs, and responsive Bootstrap 5.',
            'credits' => 3,
            'department_id' => $seDept->id,
            'semester_id' => $semester->id,
            'capacity' => 35,
        ]);

        $c4 = Course::create([
            'course_code' => 'CS305',
            'title' => 'Software Engineering Principles',
            'description' => 'Software lifecycle models, Agile methodologies, system modeling (UML), design patterns, and software quality assurance.',
            'credits' => 3,
            'department_id' => $seDept->id,
            'semester_id' => $semester->id,
            'capacity' => 35,
        ]);

        // 6. Seed Course Schedules
        CourseSchedule::create([
            'course_id' => $c1->id,
            'day_of_week' => 'Monday',
            'start_time' => '10:00 AM',
            'end_time' => '11:30 AM',
            'room' => 'Room 302',
        ]);
        CourseSchedule::create([
            'course_id' => $c1->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '10:00 AM',
            'end_time' => '11:30 AM',
            'room' => 'Room 302',
        ]);

        CourseSchedule::create([
            'course_id' => $c2->id,
            'day_of_week' => 'Monday',
            'start_time' => '01:30 PM',
            'end_time' => '03:00 PM',
            'room' => 'Lab 105',
        ]);

        CourseSchedule::create([
            'course_id' => $c3->id,
            'day_of_week' => 'Tuesday',
            'start_time' => '10:00 AM',
            'end_time' => '11:30 AM',
            'room' => 'Lab 201',
        ]);

        CourseSchedule::create([
            'course_id' => $c4->id,
            'day_of_week' => 'Tuesday',
            'start_time' => '01:30 PM',
            'end_time' => '03:00 PM',
            'room' => 'Room 401',
        ]);

        // 7. Seed Initial Registrations for Demo Student
        Registration::create([
            'student_id' => $student->id,
            'course_id' => $c1->id,
            'registered_at' => now(),
            'status' => 'registered',
        ]);

        Registration::create([
            'student_id' => $student->id,
            'course_id' => $c2->id,
            'registered_at' => now(),
            'status' => 'registered',
        ]);
    }
}
