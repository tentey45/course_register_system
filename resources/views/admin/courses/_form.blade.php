<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold small">Course Code</label>
        <input type="text" name="course_code" class="form-control bg-light border-0"
               value="{{ old('course_code', $course->course_code ?? '') }}" required>
    </div>
    <div class="col-md-8">
        <label class="form-label fw-semibold small">Title</label>
        <input type="text" name="title" class="form-control bg-light border-0"
               value="{{ old('title', $course->title ?? '') }}" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Description</label>
    <textarea name="description" rows="3" class="form-control bg-light border-0">{{ old('description', $course->description ?? '') }}</textarea>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold small">Department</label>
        <select name="department_id" class="form-select bg-light border-0" required>
            <option value="" disabled {{ old('department_id', $course->department_id ?? '') ? '' : 'selected' }}>Select department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ (old('department_id', $course->department_id ?? '') == $dept->id) ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold small">Semester</label>
        <select name="semester_id" class="form-select bg-light border-0" required>
            <option value="" disabled {{ old('semester_id', $course->semester_id ?? '') ? '' : 'selected' }}>Select semester</option>
            @foreach($semesters as $sem)
                <option value="{{ $sem->id }}" {{ (old('semester_id', $course->semester_id ?? '') == $sem->id) ? 'selected' : '' }}>
                    {{ $sem->name }} ({{ $sem->academic_year }})
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label fw-semibold small">Credits</label>
        <input type="number" name="credits" class="form-control bg-light border-0" min="1" max="10"
               value="{{ old('credits', $course->credits ?? 3) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold small">Capacity</label>
        <input type="number" name="capacity" class="form-control bg-light border-0" min="1" max="500"
               value="{{ old('capacity', $course->capacity ?? 40) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold small">Price (USD)</label>
        <input type="number" step="0.01" name="price" class="form-control bg-light border-0" min="0" max="9999.99"
               value="{{ old('price', $course->price ?? 50) }}" required>
    </div>
</div>