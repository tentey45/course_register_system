# Smart Course Registration System (SCRS)

## Developers
- **Ponhvorntey Choub** (Project Lead)
- **Vibol Vathana Chhut**

---

## Project Description
The **Smart Course Registration System (SCRS)** is a web application designed for university students to manage their course enrollments seamlessly. 

Students can:
- Login to their account
- View available courses
- Search for courses
- Register for courses
- Drop enrolled courses
- View their registered courses

---

## Project Scope
The project scope is intentionally kept small and targeted as it is developed for a university **Software Engineering** course requirement.

---

## Features
- **Authentication**: Student login
- **Course Exploration**: View Course List, Search Courses
- **Registration Management**: Register Courses, Drop Courses
- **Dashboard & Enrollment**: View Registered Courses, Student Profile

---

## Tech Stack
- **Framework**: Laravel 12
- **Language**: PHP 8.3+
- **Database**: MySQL
- **Frontend**: Blade
- **CSS**: Bootstrap 5

---

## Database Tables Overview
- `departments`
- `students`
- `admins`
- `semesters`
- `courses`
- `course_schedules`
- `registrations`

---

## Folder Structure
```text
course_register_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   └── Models/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/
│   ├── API.md
│   ├── ERD.png
│   └── README_PROJECT.md
├── public/
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── course/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── layouts/
│       │   └── app.blade.php
│       ├── registration/
│       │   └── my-course.blade.php
│       └── student/
│           └── profile.blade.php
├── routes/
│   ├── auth.php
│   └── web.php
├── storage/
├── tests/
└── README.md
```

---

## Git Branching Strategy & Conventions
Recommended branching strategy for project development:

- `main` — Production/stable releases
- `develop` — Active integration branch
- `feature/login` — Student authentication feature development
- `feature/course-list` — Course listing and search feature
- `feature/course-registration` — Course registration & drop workflows
- `feature/student-dashboard` — Dashboard and profile UI
- `feature/database` — Database schema & migrations implementation

---

## Installation (Placeholder)

> [!NOTE]
> This repository is currently in the initial project setup phase. Setup and installation instructions will be added as features and dependencies are introduced.

```bash
# 1. Clone the repository
git clone https://github.com/tentey45/course_register_system.git

# 2. Navigate to project folder
cd course_register_system

# 3. Install composer dependencies (Future step)
# composer install

# 4. Environment setup (Future step)
# cp .env.example .env
# php artisan key:generate

# 5. Database migrations (Future step)
# php artisan migrate
```
