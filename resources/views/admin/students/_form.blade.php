<div class="row g-3">
 <div class="col-md-6"><label class="form-label">Student ID</label><input class="form-control" name="student_id" value="{{ old('student_id', $student->student_id ?? '') }}" required></div>
 <div class="col-md-6"><label class="form-label">Full name</label><input class="form-control" name="name" value="{{ old('name', $student->name ?? '') }}" required></div>
 <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $student->email ?? '') }}" required></div>
 <div class="col-md-6"><label class="form-label">Department</label><select class="form-select" name="department_id" required>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(old('department_id', $student->department_id ?? '') == $department->id)>{{ $department->name }}</option>@endforeach</select></div>
 <div class="col-md-6"><label class="form-label">Gender</label><select class="form-select" name="gender" required><option value="male" @selected(old('gender', $student->gender ?? '') === 'male')>Male</option><option value="female" @selected(old('gender', $student->gender ?? '') === 'female')>Female</option></select></div>
 <div class="col-md-6"><label class="form-label">{{ isset($student) ? 'New password (optional)' : 'Password' }}</label><input type="password" class="form-control" name="password" {{ isset($student) ? '' : 'required' }}></div>
 <div class="col-md-6"><label class="form-label">Confirm password</label><input type="password" class="form-control" name="password_confirmation" {{ isset($student) ? '' : 'required' }}></div>
</div>
