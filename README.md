# Course Register System (CRS)

## Developers
- **Ponhvorntey Choub** (Project Lead)
- **Vibol Vathana Chhut**

---

## Project Description
The **Course Register System (CRS)** is a web application designed for university students to manage their course enrollments seamlessly.

Students can:
- Authenticate via password or **Google OAuth**
- View and search available courses
- Register for courses and make tuition payments via **ABA PayWay**
- View enrolled courses and class schedules
- Manage their profile and upload a custom profile picture
- Cancel pending registrations

Admins can:
- Manage academic courses, departments, and schedules
- View student rosters and course enrollments
- Monitor all online tuition payment transactions

---

## Project Scope
The project scope is focused on student course registration and online payment management as a university **Software Engineering** capstone project.

---

## Features
- **Authentication**: Student login & Google OAuth Single Sign-On
- **Course Exploration**: Browse Course Catalog, View Details & Class Schedules
- **Online Payment**: ABA PayWay integration with automatic transaction status verification
- **Registration Management**: Course Registration, Pending Registration Cancellation & Status Checking
- **Student Profile**: Manage personal details and upload custom profile image
- **Admin Dashboard**: Course management, registration audit, and payment transaction monitoring

---

## Tech Stack
- **Framework**: Laravel 12
- **Language**: PHP 8.3+
- **Database**: MySQL
- **Frontend**: Blade Templating & Bootstrap 5
- **Payment Gateway**: ABA PayWay (Payment Link & API Verification)
- **Authentication**: Native Auth & Google Socialite

---

## Domain Database Tables Overview
- `departments`
- `semesters`
- `students`
- `admins`
- `courses`
- `course_schedules`
- `registrations`
- `payments`

*For full ERD diagram and table schema details, see [`docs/DATABASE.md`](file:///c:/Users/kfumi/OneDrive/Desktop/course_register_system/docs/DATABASE.md).*

---

## Folder Structure
```text
course_register_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Services/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/
│   ├── DATABASE.md
│   ├── API.md
│   └── ERD.png
├── public/
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── courses/
│       │   └── payments/
│       ├── student/
│       │   ├── courses/
│       │   ├── payment/
│       │   └── profile.blade.php
│       └── layouts/
│           └── app.blade.php
├── routes/
│   └── web.php
└── README.md
```
