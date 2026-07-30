<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Course Registration System')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --wf-blue: #4D84F7;
            --wf-green: #10A352;
            --wf-red: #D32F2F;
            --wf-gray-bg: #F8FAFC;
            --wf-card-bg: #EAECEF;
            --wf-pill-bg: #D9D9D9;
            --wf-text-dark: #1E293B;
            --wf-text-muted: #64748B;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--wf-gray-bg);
            color: var(--wf-text-dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Desktop & Tablet Navigation Bar */
        .navbar-scrs {
            background-color: var(--wf-blue);
            box-shadow: 0 4px 12px rgba(77, 132, 247, 0.15);
        }

        .navbar-scrs .navbar-brand {
            color: #FFFFFF;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .navbar-scrs .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar-scrs .nav-link:hover,
        .navbar-scrs .nav-link.active {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Mobile Header Bar */
        .mobile-header-bar {
            background-color: var(--wf-blue);
            color: #FFFFFF;
            padding: 14px 20px;
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            position: relative;
        }

        .mobile-header-bar .back-btn {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #FFFFFF;
            font-size: 1.2rem;
            text-decoration: none;
        }

        /* Mobile Bottom Nav (< 768px) */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background-color: #C3DAFE;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-top: 1px solid #A3BFFA;
            z-index: 1030;
        }

        .mobile-bottom-nav .nav-item {
            color: #3B82F6;
            font-size: 1.4rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6px;
        }

        .mobile-bottom-nav .nav-item.active {
            color: #1D4ED8;
            transform: scale(1.1);
        }

        @media (max-width: 767.98px) {
            .main-wrapper {
                padding-bottom: 80px;
            }
        }

        .wf-card {
            background-color: var(--wf-card-bg);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .wf-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        .wf-avatar-circle {
            background-color: #334155;
            border-radius: 50%;
            display: inline-block;
        }

        .wf-pill-btn {
            background-color: var(--wf-pill-bg);
            color: #1E293B;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 600;
            text-align: center;
            display: block;
            width: 100%;
            text-decoration: none;
            margin-bottom: 12px;
            border: none;
            transition: background 0.2s;
        }

        .wf-pill-btn:hover {
            background-color: #CBD5E1;
            color: #0F172A;
        }

        .wf-badge-green {
            background-color: var(--wf-green);
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 6px;
            display: inline-block;
        }

        .wf-badge-registered {
            border: 1px solid var(--wf-green);
            color: var(--wf-green);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .wf-btn-submit {
            background-color: var(--wf-green);
            color: #FFFFFF;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border: none;
            box-shadow: 0 4px 10px rgba(16, 163, 82, 0.2);
            transition: background-color 0.2s;
        }

        .wf-btn-submit:hover {
            background-color: #0E8E47;
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    @hasSection('hide_nav')
    @else
    <!-- Desktop & Tablet Navbar -->
    <nav class="navbar navbar-expand-md navbar-scrs d-none d-md-flex">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ session('role') === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}">
                <span class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem; color: var(--wf-blue) !important;">S</span>
                Course Registration System
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#scrsNavbar" aria-controls="scrsNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="scrsNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-md-0 ms-md-4">
                    @if(session('role') === 'admin')
                        <!-- Admin Navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/departments*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}"><i class="bi bi-diagram-3 me-1"></i> Departments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/semesters*') ? 'active' : '' }}" href="{{ route('admin.semesters.index') }}"><i class="bi bi-calendar-range me-1"></i> Semesters</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/courses*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                                <i class="bi bi-book me-1"></i> Manage Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/students*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                <i class="bi bi-people me-1"></i> View Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/registrations*') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">
                                <i class="bi bi-journal-check me-1"></i> View Registrations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                <i class="bi bi-credit-card me-1"></i> View Payments
                            </a>
                        </li>
                    @else
                        <!-- Student Navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                                <i class="bi bi-house-door me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('student/courses') ? 'active' : '' }}" href="{{ route('student.courses.index') }}">
                                <i class="bi bi-book me-1"></i> Course Listing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('student/courses/my-courses') ? 'active' : '' }}" href="{{ route('student.courses.my-courses') }}">
                                <i class="bi bi-journal-check me-1"></i> My Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('student/courses/schedule') ? 'active' : '' }}" href="{{ route('student.courses.schedule') }}">
                                <i class="bi bi-calendar3 me-1"></i> Schedule
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="wf-avatar-circle" style="width: 28px; height: 28px;"></div>
                        <span class="fw-semibold">{{ session('user_name', 'User') }}</span>
                        <span class="badge bg-white text-dark ms-1 text-uppercase" style="font-size: 0.65rem;">{{ session('role', 'Student') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 border-0 mt-2">
                        @if(session('role') === 'student')
                            <li><a class="dropdown-item py-2" href="{{ route('student.profile') }}"><i class="bi bi-person me-2"></i> Student Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Top Header Bar (< 768px) -->
    <div class="d-md-none">
        @yield('header')
    </div>
    @endif

    <!-- Main Content Wrapper -->
    <div class="main-wrapper flex-grow-1">
        <main class="container-xl py-4">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Navigation (< 768px) -->
    @hasSection('hide_nav')
    @else
    @if(session('role') === 'student')
    <div class="mobile-bottom-nav d-md-none">
        <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->is('student/dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
        </a>
        <a href="{{ route('student.courses.index') }}" class="nav-item {{ request()->is('student/courses') ? 'active' : '' }}">
            <i class="bi bi-book"></i>
        </a>
        <a href="{{ route('student.courses.my-courses') }}" class="nav-item {{ request()->is('student/courses/my-courses') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i>
        </a>
        <a href="{{ route('student.courses.schedule') }}" class="nav-item {{ request()->is('student/courses/schedule') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
        </a>
        <a href="{{ route('student.profile') }}" class="nav-item {{ request()->is('student/profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
        </a>
    </div>
    @endif
    @endif

    <!-- Footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small d-none d-md-block mt-auto">
        <div class="container-xl">
            Course Registration System &copy; 2026
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
