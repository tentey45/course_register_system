<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_auth_redirects_to_google_provider(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_existing_student_can_log_in_via_google_callback(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create([
            'email' => 'existingstudent@example.com',
            'department_id' => $department->id,
            'google_id' => null,
        ]);

        $mockGoogleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockGoogleUser->shouldReceive('getId')->andReturn('google-id-12345');
        $mockGoogleUser->shouldReceive('getEmail')->andReturn('existingstudent@example.com');
        $mockGoogleUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($mockGoogleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'google_id' => 'google-id-12345',
            'avatar' => 'https://lh3.googleusercontent.com/avatar.jpg',
        ]);
        $this->assertEquals(true, session('authenticated'));
        $this->assertEquals($student->id, session('user_id'));
    }

    public function test_new_google_user_redirects_to_register_complete_form(): void
    {
        $mockGoogleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockGoogleUser->shouldReceive('getId')->andReturn('google-id-99999');
        $mockGoogleUser->shouldReceive('getEmail')->andReturn('newstudent@example.com');
        $mockGoogleUser->shouldReceive('getName')->andReturn('New Student');
        $mockGoogleUser->shouldReceive('getNickname')->andReturn(null);
        $mockGoogleUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/new_avatar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($mockGoogleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('register.complete'));
        $this->assertEquals([
            'google_id' => 'google-id-99999',
            'name' => 'New Student',
            'email' => 'newstudent@example.com',
            'avatar' => 'https://lh3.googleusercontent.com/new_avatar.jpg',
        ], session('google_pending'));
    }

    public function test_new_google_user_can_complete_registration(): void
    {
        $department = Department::factory()->create();

        $sessionData = [
            'google_pending' => [
                'google_id' => 'google-id-77777',
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'avatar' => 'https://lh3.googleusercontent.com/johndoe.jpg',
            ],
        ];

        $response = $this->withSession($sessionData)->post(route('register.complete.store'), [
            'department_id' => $department->id,
            'gender' => 'male',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('students', [
            'email' => 'johndoe@example.com',
            'google_id' => 'google-id-77777',
            'department_id' => $department->id,
            'gender' => 'male',
        ]);
        $this->assertNull(session('google_pending'));
        $this->assertEquals(true, session('authenticated'));
    }
}
