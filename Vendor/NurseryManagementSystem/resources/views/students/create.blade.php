@extends('nms::layouts.app')

@section('content')
<h3>New Student</h3>
<form method="POST" action="{{ route('students.store') }}" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">First Name</label>
      <input name="first_name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Last Name</label>
      <input name="last_name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Classroom</label>
      <select name="classroom_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($classrooms as $room)
          <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">Roll No</label>
      <input name="roll_no" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">DOB</label>
      <input type="date" name="dob" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">Gender</label>
      <input name="gender" class="form-control">
    </div>
    <div class="col-12">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="2"></textarea>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
