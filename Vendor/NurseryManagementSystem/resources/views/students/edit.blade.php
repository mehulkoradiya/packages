@extends('nms::layouts.app')

@section('content')
<h3>Edit Student</h3>
<form method="POST" action="{{ route('students.update', $student) }}" class="card p-3">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">First Name</label>
      <input name="first_name" class="form-control" value="{{ $student->first_name }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Last Name</label>
      <input name="last_name" class="form-control" value="{{ $student->last_name }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Classroom</label>
      <select name="classroom_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($classrooms as $room)
          <option value="{{ $room->id }}" @if($student->classroom_id==$room->id) selected @endif>{{ $room->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Roll No</label>
      <input name="roll_no" class="form-control" value="{{ $student->roll_no }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">DOB</label>
      <input type="date" name="dob" class="form-control" value="{{ optional($student->dob)->format('Y-m-d') }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Gender</label>
      <input name="gender" class="form-control" value="{{ $student->gender }}">
    </div>
    <div class="col-12">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="2">{{ $student->address }}</textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
